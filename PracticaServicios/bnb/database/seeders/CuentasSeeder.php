<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cuenta;

class CuentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cuentas de ejemplo
        $cuentas = [
            ['cuenta' => '1001', 'ci' => '1111111', 'nombres' => 'Juan', 'apellidos' => 'Perez', 'saldo' => 1000.0],
            ['cuenta' => '1002', 'ci' => '2222222', 'nombres' => 'Ana', 'apellidos' => 'Gomez', 'saldo' => 50.0],
            ['cuenta' => '2002', 'ci' => '3333333', 'nombres' => 'Banco', 'apellidos' => 'Destino', 'saldo' => 0.0],
        ];

        foreach ($cuentas as $c) {
            Cuenta::updateOrCreate(['cuenta' => $c['cuenta']], $c);
        }
    }
}

