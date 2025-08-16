<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpdbApplication extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ppdb_application';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_ppdb_application';

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
        'nama_pendaftar',
        'nomor_registrasi',
        'tanggal_registrasi',
        'status',
        'tingkat_terpilih_id',
        'transfer_siswa_id',
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
     * Get the Tingkat (grade) selected in this application.
     *
     * @return BelongsTo
     */
    public function tingkat(): BelongsTo
    {
        return $this->belongsTo(Tingkat::class, 'tingkat_terpilih_id', 'id_tingkat');
    }

    /**
     * Get the Siswa (student) this application is transferring from (if any).
     *
     * @return BelongsTo
     */
    public function transferSiswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'transfer_siswa_id', 'id_siswa');
    }

    /**
     * Get the user who created this application.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /**
     * Get the user who last updated this application.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_user');
    }
}
