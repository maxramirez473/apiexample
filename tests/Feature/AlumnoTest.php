<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AlumnoTest extends TestCase
{
    use RefreshDatabase;

    public function test_listar_alumnos_requiere_autenticacion(): void
    {
        $this->getJson('/api/alumnos')->assertUnauthorized();
    }

    public function test_un_usuario_autenticado_puede_listar_alumnos(): void
    {
        // actingAs evita tener que hacer login y generar un token en cada test.
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/alumnos')
            ->assertOk()
            ->assertJsonCount(0);
    }

    public function test_crear_un_alumno_valida_los_campos_obligatorios(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/alumnos', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'legajo',
                'nombres',
                'apellidos',
                'email',
                'grupo_id',
            ]);
    }

    public function test_crear_un_alumno_rechaza_email_invalido_y_grupo_inexistente(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/alumnos', [
            'legajo' => 1234,
            'nombres' => 'Ana',
            'apellidos' => 'García',
            'email' => 'esto-no-es-un-email',
            'grupo_id' => 99999,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'grupo_id']);
    }
}
