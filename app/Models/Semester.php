<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * Class Semester
 *
 * Represents a semester within a given academic year, either "ganjil" or "genap".
 *
 * @package App\Models
 *
 * @property int $id Primary key
 * @property int $tahun_pelajaran_id Foreign key referencing academic year
 * @property string $nama_semester Semester name: 'ganjil' or 'genap'
 * @property string $status Semester status: 'active' or 'inactive'
 * @property int|null $created_by ID of the user who created this record
 * @property int|null $updated_by ID of the user who last updated this record
 * @property \Illuminate\Support\Carbon|null $created_at Timestamp of creation
 * @property \Illuminate\Support\Carbon|null $updated_at Timestamp of last update
 *
 * @property-read \App\Models\TahunPelajaran $tahunPelajaran The academic year this semester belongs to
 * @property-read \App\Models\User|null $creator The user who created this semester
 * @property-read \App\Models\User|null $updater The user who last updated this semester
 *
 * @method static \Illuminate\Database\Eloquent\Builder|static query()
 * @method static \Illuminate\Database\Eloquent\Builder|static whereStatus(string $status)
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Semester extends Model
{
    use HasFactory;

    protected $table = 'semesters';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'tahun_pelajaran_id',
        'nama_semester',
        'status',
    ];

    /**
     * Automatically assign user IDs during creation and update.
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
     * Get the academic year this semester belongs to.
     *
     * @return BelongsTo
     */
    public function tahunPelajaran(): BelongsTo
    {
        return $this->belongsTo(TahunPelajaran::class, 'tahun_pelajaran_id', 'id');
    }

    /**
     * Get all of the iuran for the Semester
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function iuran(): HasMany
    {
        return $this->hasMany(Iuran::class, 'semester_id', 'id_semester');
    }

    /**
     * Get the user who created this semester.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /**
     * Get the user who last updated this semester.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_user');
    }
}
