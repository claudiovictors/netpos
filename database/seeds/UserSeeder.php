<?php

declare(strict_types=1);

use Slenix\Database\Seeds\Seeder;

/**
 * UserSeeder
 *
 * Inserts data into the corresponding table.
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $now  = date('Y-m-d H:i:s');
        $hash = password_hash('12345678', PASSWORD_DEFAULT); // o seed não passa pelo model, por isso o hash é manual
 
        $this->truncate('users');
 
        $this->insertBatch('users', [
            [
                'tenant_id'  => 1,
                'name'       => 'Cláudio Victor',
                'email'      => 'admin@netpos.test',
                'phone'      => '+244 923 000 001',
                'password'   => $hash,
                'is_admin'   => true,
                'status'     => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tenant_id'  => 1,
                'name'       => 'Caixa Demo',
                'email'      => 'caixa@netpos.test',
                'phone'      => '+244 923 000 002',
                'password'   => $hash,
                'is_admin'   => 0,
                'status'     => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}