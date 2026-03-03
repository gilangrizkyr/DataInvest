<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('users')->truncate();

        $data = [
            [
                'username' => 'admin',
                'name' => 'Administrator',
                'email' => 'admin@sst.local',
                'password' => password_hash('RealisasiInvestasi@ptsp', PASSWORD_BCRYPT, ['cost' => 12]),
                'role' => 'superadmin',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);

        echo "Admin user berhasil dibuat!\n";
        echo "Username: admin\n";
        echo "Password: RealisasiInvestasi@ptsp\n";
        echo "Email: admin@sst.local\n";
    }
}