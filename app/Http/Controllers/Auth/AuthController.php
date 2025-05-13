<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use App\Services\LogActivityService;
use App\Services\ResponseService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $logActivityService;
    protected $responseService;

    // Injecting services
    public function __construct(LogActivityService $logActivityService, ResponseService $responseService)
    {
        $this->logActivityService = $logActivityService;
        $this->responseService = $responseService;
    }

    public function loginView()
    {
        $this->logActivityService->log('Accessed the login view.'); // Log the view access
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Rate Limiting
        $attempts = RateLimiter::attempts('login:' . $request->ip(), 5, 60);

        // if ($attempts > 5) {
        //     $seconds = RateLimiter::remaining('login:' . $request->ip(), 5, 60);
        //     return response()->json(['message' => 'Too many login attempts. Please try again in ' . $seconds . ' seconds.'], 429);
        // }

        // In your Controller - Add this response to handle the rate limit timer
        if ($attempts > 5) {
            $remainingSeconds = RateLimiter::availableIn('login:' . $request->ip()); // This gets the seconds until the lockout is lifted

            return response()->json([
                'message' => 'Too many login attempts. Please try again in ' . $remainingSeconds . ' seconds.',
                'remaining_time' => $remainingSeconds // Pass the remaining time
            ], 429);
        }

        // 2. CSRF Protection (Handled by Laravel)

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 3. Authentication Logic
        $user = User::where('email', $request->email)->first();


        if ($user && Hash::check($request->password, $user->password)) {
            // 4. Session Management
            Auth::login($user, $request->has('remember'));

            // 5. Activity Logging (Successful Login)
            $this->logActivityService->log("User {$user->email} logged in."); // Use the service

            RateLimiter::clear('login:' . $request->ip());

            return response()->json(['message' => 'Login successful', 'redirect' => route('dashboard')], 200);
        } else {
            // 6. Failed Login Attempt
            RateLimiter::hit('login:' . $request->ip());
            $this->logActivityService->log("Failed login attempt for email: {$request->email} from IP: {$request->ip()}."); // Log the failed attempt

            return response()->json(['message' => 'Invalid credentials.'], 401);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user(); // Get the currently logged in user before logout.
        if ($user) {
            $this->logActivityService->log("User {$user->email} logged out."); // Log before logout
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
