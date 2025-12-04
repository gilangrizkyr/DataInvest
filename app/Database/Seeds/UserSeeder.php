<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'superadmin',
                'email' => 'superadmin@sst.local',
                'password' => password_hash('SuperAdmin@123', PASSWORD_BCRYPT, ['cost' => 12]),
                'role' => 'superadmin',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);

        echo "Superadmin user berhasil dibuat!\n";
        echo "Username: superadmin\n";
        echo "Password: SuperAdmin@123\n";
        echo "Email: superadmin@sst.local\n";
    }
}