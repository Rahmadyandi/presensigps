<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id_user' => '1',
                'nip' => '2003113950',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'id_user' => '2',
                'nip' => '2103035833',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ],
        ];
        foreach ($users as $user) {
            User::create($user);
        }
        $dosens = [
            [
                'id_user' => '1',
                'nip' => '2003113950',
                'nama_lengkap' => 'Rahmad Yandi',
                'jabatan' => 'Head of IT',
                'no_hp' => '082386953592',
                'password' => Hash::make('password'),
                'foto' => 'foto.png',
                'persetujuan' => 'diterima',
            ],
            [
                'id_user' => '2',
                'nip' => '2103035833',
                'nama_lengkap' => 'Ichsan Hanifdeal',
                'jabatan' => 'Dosen',
                'no_hp' => '081378062435',
                'password' => Hash::make('password'),
                'foto' => 'foto.png',
                'persetujuan' => 'diterima',
            ]
        ];
        foreach ($dosens as $dosen) {
            Dosen::create($dosen);
        }
    }
}
