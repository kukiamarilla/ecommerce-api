<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase {

    use RefreshDatabase;

    private $user;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);
    }
    /**
    * @test El usuario se loguea correctamente
    */
    public function test_can_login_a_user() {

        $reponse = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $reponse->assertStatus(200);
        $reponse->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);
    }

    /**
     * @test El usuario no puede loguearse con un email incorrecto
     */
    public function test_cannot_login_a_user_with_incorrect_email() {
        $reponse = $this->postJson('/api/login', [
            'email' => 'incorrect@mail.com',
            'password' => 'password',
        ]);
        $reponse->assertStatus(401);
    }

    /**
     * @test El usuario no puede loguearse con una contraseña incorrecta
     */
    public function test_cannot_login_a_user_with_incorrect_password() {
        $reponse = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'incorrect_password',
        ]);
        $reponse->assertStatus(401);
    }

    /**
     * @test El usuario no puede loguearse con una contraseña vacía
     */
    public function test_cannot_login_a_user_with_empty_password() {
        $reponse = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => '',
        ]);
        $reponse->assertStatus(401);
    }

    /**
     * @test El usuario no puede loguearse con un email vacío
     */
    public function test_cannot_login_a_user_with_empty_email() {
        $reponse = $this->postJson('/api/login', [
            'email' => '',
            'password' => 'password',
        ]);
        $reponse->assertStatus(401);
    }

    /**
     * @test El usuario puede realizar una petición GET a /api/me estando logueado
     */
    public function test_can_get_user_info_when_logged_in() {
        $token = auth()->login($this->user);
        $response = $this->postJson("/api/me", [], [
            'Authorization' => "Bearer {$token}",
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * @test El usuario no puede realizar una petición GET a /api/me estando deslogueado
     */
    public function test_cannot_get_user_info_when_not_logged_in() {
        $response = $this->postJson("/api/me");
        $response->assertStatus(401);
    }

    /**
     * @test El usuario puede desloguearse si esta logueado
     */
    public function test_can_logout_when_logged_in() {
        $token = auth()->login($this->user);
        $response = $this->postJson("/api/logout", [], [
            'Authorization' => "Bearer {$token}",
        ]);
        $response->assertStatus(200);
    }
    /**
     * @test El usuario no puede desloguearse si esta deslogueado
     */
    public function test_cannot_logout_when_not_logged_in() {
        $response = $this->postJson("/api/logout");
        $response->assertStatus(401);
    }
    /**
     * @test El usuario puede refrscar su token si esta logueado
     */
    public function test_can_refresh_token_when_logged_in() {
        $token = auth()->login($this->user);
        $response = $this->postJson("/api/refresh", [], [
            'Authorization' => "Bearer {$token}",
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);
    }
    /**
     * @test El usuario no puede refrescar su token si esta deslogueado
     */
    public function test_cannot_refresh_token_when_not_logged_in() {
        $response = $this->postJson("/api/refresh");
        $response->assertStatus(401);
    }
}
