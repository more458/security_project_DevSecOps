<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Usuarios_model;

class Login_controller extends BaseController
{
    public function index()
    {
        helper(['form', 'url']);
    }

    public function auth()
    {
        $session = session(); // iniciamos el objeto session()
        $model = new Usuarios_model(); // instanciamos el modelo

        // traemos los datos del formulario
        $email = $this->request->getVar('email'); // correo
        $password = $this->request->getVar('pass'); // password
        //$usuario = $this->request->getVar('usuario') //nombre de usuario

        $data = $model->where('email', $email)->first(); // consulta a la tabla
        if ($data) {
            $pass = $data['pass'];
            $ba = $data['baja'];
            if ($ba == 'SI') {
                $session->setFlashdata('msg', 'usuario dado de baja');
                return redirect()->to('/');
            }

            $verify_pass = password_verify($password, $pass);
            // password_verify determina los requisitos de configuración de la contra

            if ($verify_pass) {
                $ses_data = [
                    'id_usuario' => $data['id_usuario'],
                    'nombre'     => $data['nombre'],
                    'apellido'   => $data['apellido'],
                    'email'      => $data['email'],
                    'usuario'    => $data['usuario'],
                    'perfil_id'  => $data['perfil_id'],
                    'logged_in'  => TRUE
                ];
                // Se cumple la verificación e inicia la sesión
                $session->set($ses_data);

                session()->setFlashdata('msg', 'Bienvenido!!');
                return redirect()->to(base_url('Casa'));//lo redirije a la pagina principal
                // return redirect()->to('/prueba'); // página principal
            } else {
                // no pasó la validación de la password
                $session->setFlashdata('msg', 'Password Incorrecta');
                return redirect()->to(base_url('Login'));
            }
        } else {
            $session->setFlashdata('msg', 'No ingreso un email o el mismo es incorrecto');
            return redirect()->to(base_url('Login'));
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('/'));
    }
}
?>