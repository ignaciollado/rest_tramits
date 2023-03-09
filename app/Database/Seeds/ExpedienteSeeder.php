<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ExpedienteSeeder extends Seeder
{
    public function run()
	{
		for ($i = 0; $i < 10; $i++) {
		    $this->db->table('pindust_expediente')->insert($this->generateExpediente());
        }
	}

    private function generateExpediente(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'idExp' => random_int(1,99),
            'empresa' => $faker->name(),
            'email' => $faker->email,
            'nif' => random_int(10000, 100000000),
            'convocatoria' => '2023',
            'tipo_tramite' => 'IDI-ISBA'
        ];
    }
}
