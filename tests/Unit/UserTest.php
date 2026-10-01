<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

// Test unitario: no hace requests HTTP ni toca la base de datos.
// Usa Tests\TestCase (y no PHPUnit\Framework\TestCase) porque la factory
// necesita el contenedor de Laravel para hashear la contraseña.
class UserTest extends TestCase
{
    public function test_el_password_y_el_remember_token_no_se_serializan(): void
    {
        // make() construye el modelo en memoria, sin guardarlo en la base.
        $array = User::factory()->make()->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    public function test_solo_los_campos_esperados_son_asignables_en_masa(): void
    {
        $this->assertSame(
            ['name', 'email', 'password'],
            (new User())->getFillable()
        );
    }
}
