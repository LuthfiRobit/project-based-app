<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class JabatanGuru
 *
 * This model represents the 'jabatan_guru' table in the database.
 * It manages different positions or roles for teachers within the system.
 *
 * @package App\Models
 *
 * @property int $id_jabatan The primary key for the teacher's position.
 * @property string $nama_jabatan The name of the position (e.g., 'Kepala Sekolah', 'Guru Kelas').
 * @property string|null $deskripsi A brief description of the position.
 * @property string $status The current status of the position (e.g., 'active', 'inactive').
 * @property int|null $created_by ID of the user who created the record.
 * @property int|null $updated_by ID of the user who last updated the record.
 * @property \Illuminate\Support\Carbon|null $created_at Timestamp when the position was created.
 * @property \Illuminate\Support\Carbon|null $updated_at Timestamp when the position was last updated.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Guru[] $guru The teachers holding this position.
 * @property-read \App\Models\User|null $creator The user who created this position.
 * @property-read \App\Models\User|null $updater The user who last updated this position.
 * @property-read int|null $guru_count
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class JabatanGuru extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jabatan_guru';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_jabatan';

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
        'nama_jabatan',
        'deskripsi',
        'status',
        // 'created_by' and 'updated_by' are set automatically
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
     * Get all of the Guru (teachers) for the JabatanGuru (teacher position).
     *
     * @return HasMany
     */
    public function guru(): HasMany
    {
        return $this->hasMany(Guru::class, 'jabatan_id', 'id_jabatan');
    }

    /**
     * Get the user who created this JabatanGuru.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /**
     * Get the user who last updated this JabatanGuru.
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
        $query = self::select('id_jabatan', 'nama_jabatan', 'status')->orderBy('created_at', 'DESC');

        if (!empty($filters['filter_status'])) {
            $query->where('status', $filters['filter_status']);
        }

        return $query->get();
    }

    /**
     * Retrieve all active teacher positions.
     *
     * @return Collection
     */
    public static function getActive(): Collection
    {
        return self::select('id_jabatan', 'nama_jabatan', 'status')
            ->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
