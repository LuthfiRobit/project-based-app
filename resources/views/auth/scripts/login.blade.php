<script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const remember = document.getElementById('remember').checked;

        fetch('{{ route('login') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content') // Get CSRF token from meta tag
                },
                body: JSON.stringify({
                    email,
                    password,
                    remember
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || "Unknown error");
                    });
                }
                return response.json();
            })
            .then(data => {
                // Handle successful login (e.g., redirect)
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    // In case there's no redirect, show success message
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                }
            })
            .catch(error => {
                // Handle errors (e.g., display error message)
                console.error('Error:', error.message);

                // If rate limit exceeded, show throttle timer
                if (error.message.includes("Too many login attempts")) {
                    const remainingTime = error.message.match(/\d+/);
                    const throttleTimerElement = document.getElementById('throttle-timer');
                    const timerElement = document.getElementById('timer');
                    throttleTimerElement.style.display = 'block';
                    let remainingSeconds = parseInt(remainingTime[0], 10);

                    // Update the remaining time dynamically
                    const timerInterval = setInterval(function() {
                        if (remainingSeconds <= 0) {
                            clearInterval(timerInterval);
                            // When timer finishes, hide the timer and show a "try again" message
                            throttleTimerElement.style.display = 'none'; // Hide the timer

                        } else {
                            timerElement.innerHTML = remainingSeconds;
                            remainingSeconds--;
                        }
                    }, 1000);

                    Swal.fire({
                        title: 'Terlalu banyak percobaan!',
                        text: `Silakan coba lagi dalam ${remainingTime[0]} detik.`,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: error.message,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            });
    });
</script>
