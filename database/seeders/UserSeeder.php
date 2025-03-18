<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $this->createUsers('Juan', 'Perez');
        $this->createUsers('Maria', 'Gomez');
        $this->createUsers('Pedro', 'Rodriguez');
        $this->createUsers('Ana', ' Jimenez');
        $this->createUsers('Luis', 'Lopez');
        $this->createUsers('Carlos', 'Garcia');
        $this->createUsers('Sara', 'Martinez');
        $this->createUsers('Pablo', 'Hernandez');
    }

    private function createUsers(String $nombre, String $apellido): void
    {
        $user = new User([
            'name' => $nombre,
            'lastName' => $apellido,
            'email' => strtolower($nombre . $apellido . '@google.com'),
            'userName' => strtolower($nombre . $apellido),
            'password' => bcrypt('1234'),
            'last_login_at' => null,
            'last_login_before' => null,
            'new_messages_count' => 0,
        ]);

        $user->save();
    }
}
