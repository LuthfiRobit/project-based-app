<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guru extends Model
{
    use HasFactory;

    // Nama tabel yang akan digunakan oleh model
    protected $table = 'guru';
    protected $primaryKey = 'id_guru';

    // Kolom yang dapat diisi mass-assignment
    protected $fillable = [
        'jabatan_id', // Relasi ke jabatan
        'nama_guru',
        'nip',
        'alamat',
        'no_telepon',
        'email',
        'tanggal_lahir',
        'jenis_kelamin',
        'pendidikan_terakhir',
        'status_guru',
        'tanggal_masuk',
        'status_pernikahan',
        'foto',
        'status'
    ];

    /**
     * Get the jabatan that owns the Guru
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(JabatanGuru::class, 'jabatan_id', 'id_jabatan');
    }

    public static function getFilters(array $filters = [])
    {
        $query = self::select(
            'id_guru',
            'nip',
            'nama_guru',
            'no_telepon',
            'status_guru',
            'guru.status',
            'jabatan_guru.nama_jabatan as jabatan'
        )
            ->leftJoin('jabatan_guru', 'jabatan_guru.id_jabatan', '=', 'guru.jabatan_id')
            ->orderBy('guru.created_at', 'DESC');

        if (!empty($filters['filter_status']) && $filters['filter_status'] != '') {
            $query->where('guru.status', $filters['filter_status']);
        }

        if (!empty($filters['filter_status_guru']) && $filters['filter_status_guru'] != '') {
            $query->where('status_guru', $filters['filter_status_guru']);
        }

        return $query; // Kembalikan query builder, bukan paginator
    }

    public static function getRelationship($id)
    {
        $query = self::select(
            'id_guru',
            'jabatan_id',
            'nama_guru',
            'nip',
            'alamat',
            'no_telepon',
            'email',
            'tanggal_lahir',
            'jenis_kelamin',
            'pendidikan_terakhir',
            'status_guru',
            'tanggal_masuk',
            'status_pernikahan',
            'foto',
            'guru.status',
            'jabatan_guru.nama_jabatan as jabatan'
        )
            ->leftJoin('jabatan_guru', 'jabatan_guru.id_jabatan', '=', 'guru.jabatan_id')
            ->where('id_guru', $id)->first();
        return $query; // Kembalikan query builder, bukan paginator
    }
}
