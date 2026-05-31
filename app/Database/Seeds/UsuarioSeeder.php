<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory; // se importa la clase faker para usar el seeders

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('es_ES'); // faker pero en español

        //manejamos el limite y el array de datos randoms
        $limite = 5;
        $usuarios_randoms = [];

        for ($i = 0; $i < $limite; $i++) {
            $nombre = $faker->firstName();
            $apellido = $faker->lastName();
            // tenemos nombre de usuario combinando la primer letra del nombre, apelldo y un numero aleatorio
            $usuario = strtolower(substr($nombre, 0, 1) . $apellido . $faker->randomNumber(2));
            // aca email combinando nombre, apellido y un dominio de email cualquiera
            $email = strtolower($nombre . '.' . $apellido . '@' . $faker->freeEmailDomain());

            $usuarios_randoms[] = [
                'nombre'      => $nombre,
                'apellido'    => $apellido,
                'usuario'     => $usuario,
                'email'       => $email,
                'pass'        => password_hash('password', PASSWORD_DEFAULT), // con genérica para cliente
                'perfil_id'   => 2, 
                'baja'        => 'NO', 
            ];
        }

        // inserta en la tabla usuarios
        $this->db->table('usuarios')->insertBatch($usuarios_randoms);
    }
}