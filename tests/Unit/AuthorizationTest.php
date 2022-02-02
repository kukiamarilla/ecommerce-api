<?php

namespace Tests\Unit;

use App\Constants\Permissions;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test Existe un usuario administrador por defecto
     */
    public function test_user_admin_exist()
    {
        $this->seed();
        $this->assertDatabaseHas('users', [
            'name' => 'Administrador',
        ]);
    }

    /**
     * @test El usuario administrador tiene el rol Admin
     */
    public function test_user_admin_has_admin_role()
    {
        $this->seed();
        $user = User::where('name', 'Administrador')->first();
        $this->assertTrue($user->hasRole('Admin'));
    }

    /**
     * @test El usuario administrador tiene todos los permisos
     */
    public function test_user_admin_has_all_permissions()
    {
        $this->seed();
        $user = User::where('name', 'Administrador')->first();
        $permissions = $user->getAllPermissions()->map(function ($permission) {
            return $permission->name;
        })->toArray();
        $this->assertEqualsCanonicalizing($permissions, Permissions::all());
    }
}
