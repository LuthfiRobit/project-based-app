<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';
    protected $primaryKey = 'id_role';
    public $timestamps = true;
    protected $fillable = ['role_name', 'role_description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id'); // Fix foreign keys
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id'); // Fix foreign keys
    }

    public static function getRolePermissions($id)
    {
        $query = DB::table('role_permission')
            ->join('permission', 'role_permission.permission_id', '=', 'permission.id_permission')
            ->where('role_permission.role_id', $id)
            ->select('role_permission.permission_id', 'permission.permission_name')
            ->get();
        return $query;
    }
}
