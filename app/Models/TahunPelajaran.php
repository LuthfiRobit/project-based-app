<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class TahunPelajaran
 *
 * Represents an academic year in the system, e.g., "2025/2026".
 *
 * @package App\Models
 *
 * @property int $id Primary key
 * @property string $nama_tahun_pelajaran Name of the academic year (e.g., "2025/2026")
 * @property string $status Status of the academic year: 'active' or 'inactive'
 * @property int|null $created_by ID of the user who created this record
 * @property int|null $updated_by ID of the user who last updated this record
 * @property \Illuminate\Support\Carbon|null $created_at Timestamp of creation
 * @property \Illuminate\Support\Carbon|null $updated_at Timestamp of last update
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Semester[] $semesters Semesters under this academic year
 * @property-read \App\Models\User|null $creator The user who created this academic year
 * @property-read \App\Models\User|null $updater The user who last updated this academic year
 *
 * @method static \Illuminate\Database\Eloquent\Builder|static query()
 * @method static \Illuminate\Database\Eloquent\Builder|static whereStatus(string $status)
 * @method static \Illuminate\Database\Eloquent\Builder|static latest(string $column = 'created_at')
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TahunPelajaran extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tahun_pelajaran';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_tahun_pelajaran';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    protected $fillable = [
        'nama_tahun_pelajaran',
        'status',
    ];

    /**
     * Automatically set created_by and updated_by when creating or updating.
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
     * Get all semesters associated with this academic year.
     *
     * @return HasMany
     */
    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class, 'tahun_pelajaran_id', 'id_tahun_pelajaran');
    }

    /**
     * Get the user who created this academic year.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /**
     * Get the user who last updated this academic year.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_user');
    }

    /**
     * Retrieve all active academic years.
     *
     * @return Collection
     */
    public static function getActive(): Collection
    {
        return self::select('id_tahun_pelajaran', 'nama_tahun_pelajaran', 'status')
            ->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    /**
     * Retrieve filtered academic years by status.
     *
     * @param array<string, string> $filters
     * @return Collection
     */
    public static function getFilters(array $filters = []): Collection
    {
        $query = self::select('id_tahun_pelajaran', 'nama_tahun_pelajaran', 'status')->orderBy('created_at', 'DESC');

        if (!empty($filters['filter_status'])) {
            $query->where('status', $filters['filter_status']);
        }

        return $query->get();
    }
}
