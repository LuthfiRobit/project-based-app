<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Class Keringanan
 *
 * Represents a fee discount or relief option.
 *
 * @package App\Models
 *
 * @property int $id_keringanan Primary key
 * @property string $nama_keringanan Name of the discount/relief
 * @property string $status 'active' or 'inactive'
 * @property int|null $created_by ID of the user who created the record
 * @property int|null $updated_by ID of the user who last updated the record
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\User|null $updater
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Keringanan extends Model
{
    use HasFactory;

    protected $table = 'keringanan';

    protected $primaryKey = 'id_keringanan';

    public $timestamps = true;

    protected $fillable = [
        'nama_keringanan',
        'status',
    ];

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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_user');
    }
}
