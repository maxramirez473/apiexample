<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    // Crea las tablas antes de cada test y las deja limpias al terminar.
    use RefreshDatabase;

    public function test_un_usuario_puede_registrarse_y_recibe_un_token(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Juan Perez',
            'email' => 'juan@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token'])
            ->assertJsonMissingPath('user.password');

        $this->assertDatabaseHas('users', ['email' => 'juan@example.com']);
    }

    public function test_el_registro_valida_los_campos_obligatorios(): void
    {
        $this->postJson('/api/register', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_el_registro_rechaza_un_email_repetido(): void
    {
        $existente = User::factory()->create();

        $this->postJson('/api/register', [
            'name' => 'Otro Usuario',
            'email' => $existente->email,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_un_usuario_puede_iniciar_sesion(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ])
            ->assertOk()
            ->assertJsonStructure(['user' => ['id', 'email'], 'token']);
    }

    public function test_el_login_con_credenciales_invalidas_devuelve_401(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'clave-incorrecta',
        ])
            ->assertStatus(401)
            ->assertJson(['message' => 'Credenciales inválidas']);
    }

    public function test_una_ruta_protegida_rechaza_requests_sin_token(): void
    {
        $this->getJson('/api/user')->assertUnauthorized();
    }

    public function test_una_ruta_protegida_acepta_un_token_valido(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/user')
            ->assertOk()
            ->assertJson(['email' => $user->email]);
    }

    public function test_el_logout_elimina_los_tokens_del_usuario(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJson(['message' => 'Sesión cerrada']);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
