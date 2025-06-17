<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAccountService
{
    /**
     * Create a user and assign role for Guru.
     */
    public function createGuruAccount(object $guru, string $status = 'active'): User
    {
        return $this->createUserWithRole(
            name: $guru->nama_guru,
            email: $guru->email,
            status: $status,
            username: $guru->nip,
            password: $guru->nip,
            roleName: 'guru'
        );
    }

    /**
     * Create a user and assign role for Siswa.
     */
    public function createSiswaAccount(object $siswa, string $status = 'active'): User
    {
        return $this->createUserWithRole(
            name: $siswa->nama_siswa,
            email: $siswa->email,
            status: $status,
            username: $siswa->nis,
            password: $siswa->nis,
            roleName: 'siswa'
        );
    }

    /**
     * Create a user and assign role for Pegawai.
     */
    public function createPegawaiAccount(object $pegawai, string $status = 'active'): User
    {
        return $this->createUserWithRole(
            name: $pegawai->nama_pegawai,
            email: $pegawai->email,
            status: $status,
            roleName: 'pegawai'
        );
    }

    /**
     * Generic user creation + role assignment
     */
    protected function createUserWithRole(
        string $name,
        ?string $email,
        string $status,
        string $roleName,
        ?string $username = null,
        ?string $password = null
    ): User {
        if (!$email) {
            throw new Exception("Email tidak boleh kosong untuk user dengan role $roleName");
        }

        $user = User::create([
            'name'     => $name,
            'email'    => $email,
            'username' => $username ?? $this->generateUsername($name),
            'password' => Hash::make($password ?? $roleName . '123'),
            'status'   => $status,
        ]);

        $roleId = Role::where('role_name', $roleName)->value('id_role');

        if (!$roleId) {
            throw new Exception("Role '$roleName' tidak ditemukan.");
        }

        UserRole::create([
            'user_id' => $user->id_user,
            'role_id' => $roleId,
        ]);

        return $user;
    }

    /**
     * Generate unique username based on name.
     */
    protected function generateUsername(string $name): string
    {
        return Str::slug($name) . rand(100, 999);
    }
}
