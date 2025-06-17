<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection; // Add this for type hinting in static methods

/**
 * Class Guru
 *
 * This model represents the 'guru' (teacher) table in the database.
 * It stores detailed information about teachers, including their personal data,
 * employment status, and relationships to user accounts and job positions.
 *
 * @package App\Models
 *
 * @property int $id_guru The primary key for the teacher.
 * @property int $jabatan_id Foreign key linking to the JabatanGuru model (teacher's position).
 * @property int|null $user_id Foreign key linking to the User model (associated user account), can be null if not linked.
 * @property string $nama_guru The full name of the teacher.
 * @property string|null $nip The NIP (Nomor Induk Pegawai) or employee identification number of the teacher.
 * @property string|null $alamat The address of the teacher.
 * @property string|null $no_telepon The phone number of the teacher.
 * @property string|null $email The email address of the teacher.
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir The birth date of the teacher.
 * @property string|null $jenis_kelamin The gender of the teacher (e.g., 'Laki-laki', 'Perempuan').
 * @property string|null $pendidikan_terakhir The last education level of the teacher.
 * @property string|null $status_guru The employment status of the teacher (e.g., 'PNS', 'Honorer', 'Kontrak').
 * @property \Illuminate\Support\Carbon|null $tanggal_masuk The date the teacher joined the institution.
 * @property string|null $status_pernikahan The marital status of the teacher.
 * @property string|null $foto The file path or URL to the teacher's photo.
 * @property string $status The general status of the teacher record (e.g., 'active', 'inactive').
 * @property \Illuminate\Support\Carbon|null $created_at Timestamp when the record was created.
 * @property \Illuminate\Support\Carbon|null $updated_at Timestamp when the record was last updated.
 *
 * @property-read \App\Models\JabatanGuru $jabatan The associated JabatanGuru model.
 * @property-read \App\Models\User|null $user The associated User model.
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Guru extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'guru';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_guru';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true; // Assuming 'created_at' and 'updated_at' columns exist in the table

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jabatan_id',
        'user_id',
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
        'status',
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
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_masuk' => 'date',
        ];
    }

    /**
     * Get the jabatan (position) that owns the Guru.
     *
     * Defines a many-to-one relationship with the JabatanGuru model.
     * The 'jabatan_id' foreign key on the 'guru' table refers to 'id_jabatan' on the 'jabatan_guru' table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(JabatanGuru::class, 'jabatan_id', 'id_jabatan');
    }

    /**
     * Get the user that owns the Guru.
     *
     * Defines a many-to-one relationship with the User model.
     * The 'user_id' foreign key on the 'guru' table refers to 'id_user' on the 'users' table.
     * This relationship can be null if a guru record is not associated with a user account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Retrieve a query builder for filtered teacher data.
     *
     * This static method constructs a query to fetch teacher information with optional filters
     * for 'guru.status' and 'status_guru'. It includes a left join to retrieve the
     * 'nama_jabatan' from the 'jabatan_guru' table.
     *
     * @param array<string, string> $filters An associative array of filters.
     * Expected keys: 'filter_status' (for guru.status), 'filter_status_guru' (for status_guru).
     * @return \Illuminate\Database\Eloquent\Builder The Eloquent query builder instance.
     */
    public static function getFilters(array $filters = []): Builder
    {
        $query = self::select(
            'id_guru',
            'nip',
            'nama_guru',
            'no_telepon',
            'status_guru',
            'guru.status', // Specify table to avoid ambiguity
            'jabatan_guru.nama_jabatan as jabatan'
        )
            ->leftJoin('jabatan_guru', 'jabatan_guru.id_jabatan', '=', 'guru.jabatan_id')
            ->orderBy('guru.created_at', 'DESC'); // Specify table to avoid ambiguity

        if (!empty($filters['filter_status'])) {
            $query->where('guru.status', $filters['filter_status']); // Specify table to avoid ambiguity
        }

        if (!empty($filters['filter_status_guru'])) {
            $query->where('status_guru', $filters['filter_status_guru']);
        }

        return $query;
    }

    /**
     * Retrieve a single teacher record with its related jabatan (position) details.
     *
     * This static method fetches a specific teacher by their ID, including their
     * associated job position name through a left join.
     *
     * @param int $id The ID of the guru (teacher) to retrieve.
     * @return \App\Models\Guru|null The Guru model instance if found, otherwise null.
     */
    public static function getRelationship(int $id): ?self
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
            'guru.status', // Specify table to avoid ambiguity
            'jabatan_guru.nama_jabatan as jabatan'
        )
            ->leftJoin('jabatan_guru', 'jabatan_guru.id_jabatan', '=', 'guru.jabatan_id')
            ->where('id_guru', $id)
            ->first();

        return $query;
    }
}
