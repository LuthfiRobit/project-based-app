<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class Iuran
 *
 * Represents a fee/payment record associated with a specific tahunPelajaran.
 *
 * @package App\Models
 *
 * @property int $id_iuran Primary key
 * @property int $tahun_pelajaran_id Foreign key referencing tahun 
 * @property string $nama_iuran Name of the fee
 * @property int $nominal_iuran Amount (e.g., 150000)
 * @property string $status Status: 'active' or 'inactive'
 * @property int|null $created_by ID of user who created the record
 * @property int|null $updated_by ID of user who last updated the record
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Semester $tahunPelajaran
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
        'tahun_pelajaran_id',
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
     * Get the academic year this tahunPelajaran belongs to.
     *
     * @return BelongsTo
     */
    public function tahunPelajaran(): BelongsTo
    {
        return $this->belongsTo(TahunPelajaran::class, 'tahun_pelajaran_id', 'id_tahun_pelajaran');
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

    /**
     * Retrieve filtered academic years by status.
     *
     * @param array<string, string> $filters
     * @return Collection
     */
    public static function getFilters(array $filters = []): Collection
    {
        $query = self::select('id_iuran', 'nama_iuran', 'nominal_iuran', 'tahun_pelajaran.nama_tahun_pelajaran', 'iuran.status')
            ->leftJoin('tahun_pelajaran', 'iuran.tahun_pelajaran_id', 'tahun_pelajaran.id_tahun_pelajaran')
            ->orderBy('iuran.created_at', 'DESC');

        if (!empty($filters['filter_status'])) {
            $query->where('iuran.status', $filters['filter_status']);
        }

        if (!empty($filters['filter_tahun'])) {
            $query->where('iuran.tahun_pelajaran_id', $filters['filter_tahun']);
        }

        return $query->get();
    }

    public static function getRelationship(int $id): ?self
    {
        $query = self::select('id_iuran', 'nama_iuran', 'nominal_iuran', 'tahun_pelajaran.nama_tahun_pelajaran', 'iuran.status')
            ->leftJoin('tahun_pelajaran', 'iuran.tahun_pelajaran_id', 'tahun_pelajaran.id_tahun_pelajaran')
            ->where('id_iuran', $id)
            ->first();

        return $query;
    }
}
