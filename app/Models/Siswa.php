<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Siswa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'siswa';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_siswa';

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
        'user_id',
        'nis',
        'nisn',
        'nama_siswa',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'no_telepon',
        'nama_ayah',
        'nama_ibu',
        'nama_wali',
        'status',
        'tanggal_diterima',
        'tanggal_lulus',
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
     * Get the user associated with this Siswa.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Get all KelasSiswa (class history) records for this Siswa.
     *
     * @return HasMany
     */
    public function kelas(): HasMany
    {
        return $this->hasMany(KelasSiswa::class, 'siswa_id', 'id_siswa');
    }

    /**
     * Get the most recent KelasSiswa (class assignment).
     *
     * @return HasOne
     */
    public function latestKelas(): HasOne
    {
        return $this->hasOne(KelasSiswa::class, 'siswa_id', 'id_siswa')->latest();
    }

    /**
     * Get the currently active class for this Siswa.
     *
     * @return HasOne
     */
    public function currentClassroom(): HasOne
    {
        return $this->hasOne(KelasSiswa::class, 'siswa_id', 'id_siswa')
            ->where('status', 'active')
            ->with(['classroom.grade']);
    }

    /**
     * Scope query for view-ready student data with class and major details.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeViewData($query)
    {
        return $query->select([
            'siswa.id_siswa',
            'siswa.nama_siswa AS nama',
            'siswa.nis',
            'siswa.nisn',
            'siswa.jenis_kelamin',
            'siswa.tanggal_lahir',
            'siswa.tempat_lahir',
            'siswa.status',
            'tingkat.nama_tingkat AS tingkat',
            'ruang_kelas.nama_ruang_kelas AS kelas',
            'jurusan.nama_jurusan AS jurusan',
        ])
            ->leftJoin('kelas_siswa', 'siswa.id_siswa', '=', 'kelas_siswa.siswa_id')
            ->leftJoin('ruang_kelas', 'kelas_siswa.ruang_kelas_id', '=', 'ruang_kelas.id_ruang_kelas')
            ->leftJoin('tingkat', 'ruang_kelas.tingkat_id', '=', 'tingkat.id_tingkat')
            ->leftJoin('jurusan', 'ruang_kelas.jurusan_id', '=', 'jurusan.id_jurusan')
            ->where('kelas_siswa.status', 'active');
    }

    /**
     * Retrieve filtered student data.
     *
     * @param array<string, string> $filters
     * @return Collection
     */
    public static function getFilters(array $filters = []): Collection
    {
        $query = self::select([
            'siswa.id_siswa',
            'siswa.nama_siswa as nama',
            'siswa.nis',
            'siswa.nisn',
            'siswa.jenis_kelamin',
            'siswa.tanggal_lahir',
            'siswa.tempat_lahir',
            'siswa.status',
            'ruang_kelas.nama_ruang_kelas as kelas',
            'tingkat.nama_tingkat as tingkat',
            'jurusan.nama_jurusan as jurusan',
        ])
            ->leftJoin('kelas_siswa', function ($join) {
                $join->on('kelas_siswa.siswa_id', '=', 'siswa.id_siswa')
                    ->where('kelas_siswa.status', '=', 'active');
            })
            ->leftJoin('ruang_kelas', 'kelas_siswa.ruang_kelas_id', '=', 'ruang_kelas.id_ruang_kelas')
            ->leftJoin('tingkat', 'tingkat.id_tingkat', '=', 'ruang_kelas.tingkat_id')
            ->leftJoin('jurusan', 'jurusan.id_jurusan', '=', 'ruang_kelas.jurusan_id')
            ->orderBy('siswa.nama_siswa', 'ASC');

        if (!empty($filters['status'])) {
            $query->where('siswa.status', $filters['status']);
        }

        if (!empty($filters['kelas'])) {
            $query->where('ruang_kelas.nama_ruang_kelas', $filters['kelas']);
        }

        if (!empty($filters['tingkat'])) {
            $query->where('tingkat.nama_tingkat', $filters['tingkat']);
        }

        if (!empty($filters['jurusan'])) {
            $query->where('jurusan.nama_jurusan', $filters['jurusan']);
        }

        return $query->get();
    }

    /**
     * Get the user who created this Siswa.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /**
     * Get the user who last updated this Siswa.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_user');
    }
}
