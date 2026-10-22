<?php

declare(strict_types=1);

use Slenix\Database\Seeds\Seeder;

/**
 * TenantSeeder
 *
 * Inserts data into the corresponding table.
 */
class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');
 
        $this->insertBatch('tenants', [
            [
                'name'       => 'NetPos Demo, Lda',
                'email'      => 'demo@netpos.test',
                'phone'      => '+244 923 000 000',
                'nif'        => '5000000001',
                'address'    => 'Rua da Demonstração, Luanda',
                'currency'   => 'AOA',
                'status'     => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}