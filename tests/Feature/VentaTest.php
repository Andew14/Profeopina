<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VentaTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function una_venta_puede_ser_creada()
    {
        $response = $this->post('/ventas', [
            'nombre_producto' => 'Producto Test',
            'cantidad' => 10,
            'precio' => 100.00,
            'cliente' => 'Cliente Test',
            'fecha_venta' => '2024-08-12',
        ]);

        $response->assertStatus(302); // Redirecciona al index después de la creación
        $this->assertDatabaseHas('ventas', [
            'nombre_producto' => 'Producto Test',
            'cantidad' => 10,
            'precio' => 100.00,
            'cliente' => 'Cliente Test',
            'fecha_venta' => '2024-08-12',
        ]);
    }
}
