<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class RuangKelas extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ruang_kelas';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_ruang_kelas';

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
        'tahun_pelajaran_id',
        'tingkat_id',
        'jurusan_id',
        'nama_ruang_kelas',
        'wali_kelas_id',
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
     * Get the TahunPelajaran (academic year) this RuangKelas belongs to.
     *
     * @return BelongsTo
     */
    public function tahunPelajaran(): BelongsTo
    {
        return $this->belongsTo(TahunPelajaran::class, 'tahun_pelajaran_id', 'id_tahun_pelajaran');
    }

    /**
     * Get the Tingkat (grade level) this RuangKelas belongs to.
     *
     * @return BelongsTo
     */
    public function tingkat(): BelongsTo
    {
        return $this->belongsTo(Tingkat::class, 'tingkat_id', 'id_tingkat');
    }

    /**
     * Get the Jurusan (major) this RuangKelas belongs to.
     *
     * @return BelongsTo
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id', 'id_jurusan');
    }

    /**
     * Get the Guru (teacher) who is the WaliKelas (homeroom teacher).
     *
     * @return BelongsTo
     */
    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id', 'id_guru');
    }

    /**
     * Get all active KelasSiswa (class members) for this RuangKelas.
     *
     * @return HasMany
     */
    public function siswaAktif(): HasMany
    {
        return $this->hasMany(KelasSiswa::class, 'ruang_kelas_id', 'id_ruang_kelas')
            ->where('status', 'active');
    }

    /**
     * Get the user who created this RuangKelas.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /**
     * Get the user who last updated this RuangKelas.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_user');
    }

    public static function getFilters(array $filters = []): Collection
    {
        $query = self::select([
            'ruang_kelas.id_ruang_kelas',
            'ruang_kelas.nama_ruang_kelas',
            'tingkat.nama_tingkat',
            'jurusan.nama_jurusan',
            'tahun_pelajaran.nama_tahun_pelajaran',
            'guru.nama_guru as wali_kelas',
            'ruang_kelas.status',
        ])
            ->leftJoin('tingkat', 'ruang_kelas.tingkat_id', '=', 'tingkat.id_tingkat')
            ->leftJoin('jurusan', 'ruang_kelas.jurusan_id', '=', 'jurusan.id_jurusan')
            ->leftJoin('tahun_pelajaran', 'ruang_kelas.tahun_pelajaran_id', '=', 'tahun_pelajaran.id_tahun_pelajaran')
            ->leftJoin('guru', 'ruang_kelas.wali_kelas_id', '=', 'guru.id_guru')
            ->orderBy('ruang_kelas.created_at', 'desc');

        if (!empty($filters['filter_status'])) {
            $query->where('ruang_kelas.status', $filters['filter_status']);
        }

        return $query->get();
    }

    public static function getRelationship(int $id): ?self
    {
        return self::select([
            'ruang_kelas.id_ruang_kelas',
            'ruang_kelas.nama_ruang_kelas',
            'ruang_kelas.status',
            'ruang_kelas.tahun_pelajaran_id',
            'tahun_pelajaran.nama_tahun_pelajaran',
            'ruang_kelas.tingkat_id',
            'tingkat.nama_tingkat',
            'ruang_kelas.jurusan_id',
            'jurusan.nama_jurusan',
            'ruang_kelas.wali_kelas_id',
            'guru.nama_guru as wali_kelas',
        ])
            ->leftJoin('tingkat', 'ruang_kelas.tingkat_id', '=', 'tingkat.id_tingkat')
            ->leftJoin('jurusan', 'ruang_kelas.jurusan_id', '=', 'jurusan.id_jurusan')
            ->leftJoin('tahun_pelajaran', 'ruang_kelas.tahun_pelajaran_id', '=', 'tahun_pelajaran.id_tahun_pelajaran')
            ->leftJoin('guru', 'ruang_kelas.wali_kelas_id', '=', 'guru.id_guru')
            ->where('ruang_kelas.id_ruang_kelas', $id)
            ->first();
    }
}
