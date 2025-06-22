<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Tingkat extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tingkat';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_tingkat';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_tingkat',
        'jenjang',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Boot method for the model to handle automatic user attribution.
     *
     * @return void
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
     * Get all of the RuangKelas (classrooms) for the Tingkat (grade level).
     *
     * @return HasMany
     */
    public function ruangKelas(): HasMany
    {
        return $this->hasMany(RuangKelas::class, 'tingkat_id', 'id_tingkat');
    }

    /**
     * Get the user who created this Tingkat.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /**
     * Get the user who last updated this Tingkat.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_user');
    }

    /**
     * Retrieve filtered teacher positions.
     *
     * @param array<string, string> $filters
     * @return Collection
     */
    public static function getFilters(array $filters = []): Collection
    {
        $query = self::select('id_tingkat', 'nama_tingkat', 'jenjang', 'status')->orderBy('created_at', 'DESC');

        if (!empty($filters['filter_jenjang'])) {
            $query->where('jenjang', $filters['filter_jenjang']);
        }

        if (!empty($filters['filter_status'])) {
            $query->where('status', $filters['filter_status']);
        }

        return $query->get();
    }
}
