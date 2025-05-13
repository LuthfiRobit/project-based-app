<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JabatanGuru extends Model
{
    use HasFactory;

    // Nama tabel yang akan digunakan oleh model
    protected $table = 'jabatan_guru';
    protected $primaryKey = 'id_jabatan';

    // Kolom yang dapat diisi mass-assignment
    protected $fillable = [
        'nama_jabatan',
        'deskripsi',
        'status'
    ];

    /**
     * Get all of the guru for the JabatanGuru
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function guru(): HasMany
    {
        return $this->hasMany(Guru::class, 'jabatan_id', 'id_jabatan');
    }

    public static function getFilters(array $filters = [])
    {
        $query = self::select('id_jabatan', 'nama_jabatan', 'status')->orderBy('created_at', 'DESC');

        if (!empty($filters['filter_status']) && $filters['filter_status'] != '') {
            $query->where('status', $filters['filter_status']);
        }

        return $query->get();
    }

    public static function getActive()
    {
        $query = self::select('id_jabatan', 'nama_jabatan', 'status')->where('status', 'active')->orderBy('created_at', 'DESC');

        return $query->get();
    }
}
