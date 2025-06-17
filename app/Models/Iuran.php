<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Class Iuran
 *
 * Represents a fee/payment record associated with a specific semester.
 *
 * @package App\Models
 *
 * @property int $id_iuran Primary key
 * @property int $semester_id Foreign key referencing semester
 * @property string $nama_iuran Name of the fee
 * @property int $nominal_iuran Amount (e.g., 150000)
 * @property string $status Status: 'active' or 'inactive'
 * @property int|null $created_by ID of user who created the record
 * @property int|null $updated_by ID of user who last updated the record
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Semester $semester
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\User|null $updater
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Iuran extends Model
{
    use HasFactory;

    protected $table = 'iuran';

    protected $primaryKey = 'id_iuran';

    public $timestamps = true;

    protected $fillable = [
        'semester_id',
        'nama_iuran',
        'nominal_iuran',
        'status',
    ];

    /**
     * Automatically assign created_by and updated_by.
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    /**
     * Get the semester that this fee belongs to.
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id_semester');
    }

    /**
     * Get the user who created this iuran.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /**
     * Get the user who last updated this iuran.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_user');
    }
}
