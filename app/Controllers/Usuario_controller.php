<?php

namespace App\Controllers;
use App\Models\Usuarios_model;
use App\Models\Perfiles_model;
use App\Models\consulta_model;
use CodeIgniter\Controller;

class Usuario_controller extends Controller{
    public function __construct()
    {
        helper(['form', 'url']);
    }

    public function create(){
        $dato['titulo']='Registro';
        echo view('front/header_view', $dato);
        echo view('front/nav_view');
        echo view('back/usuario/registro');
        echo view('front/footer_view');
    }

    public function formValidation(){
        $input = $this->validate([
            'nombre'    => 'required|min_length[3]',
            'apellido'  => 'required|min_length[3]|max_length[50]',
            'usuario'   => 'required|min_length[3]',
            'email'     => 'required|min_length[4]|max_length[100]|valid_email|is_unique[usuarios.email]',
            'pass'      => 'required|min_length[3]|max_length[10]'
        ]);

        $formModel = new Usuarios_model();

        if (!$input) {
            $data['titulo'] = 'registro';
            echo view('front/header_view', $data);
            echo view('front/nav_view');
            echo view('back/usuario/registro', ['validation' => $this->validator]);
            echo view('front/footer_view');
        } else {
            $formModel->save([
                'nombre'    => $this->request->getVar('nombre'),
                'apellido'  => $this->request->getVar('apellido'),
                'usuario'   => $this->request->getVar('usuario'),
                'email'     => $this->request->getVar('email'),
                'pass'      => password_hash($this->request->getVar('pass'), PASSWORD_DEFAULT),
                'perfil_id' => 2,
                'baja' => 'NO'
            ]);
            session()->setFlashdata('succes', 'Usuario registrado con exito');
            return redirect()->to(base_url('registro'));
        }
    }

    public function modoAdmin(){
        $usuarios = new Usuarios_model();
        $dato['usuarios'] = $usuarios->getUsuarioAll();

        $data['titulo'] = 'Crud de usuarios';
        echo view('front/header_view', $data);
        echo view('front/nav_view');
        echo view('back/usuario/crud_usuario', $dato);
        echo view('front/footer_view');
    }

    public function usuariosEliminados(){
        $usuarios = new Usuarios_model();
        $dato['usuarios'] = $usuarios->getUsuarioElimAll();

        $data['titulo'] = 'Usuarios baneados';
        echo view('front/header_view', $data);
        echo view('front/nav_view');
        echo view('back/usuario/usuarios_ban', $dato);
        echo view('front/footer_view');
    }

    public function deleteUsuario($id)
    {
        $usuarioModel = new Usuarios_model();
        $data = ['baja' => 'SI'];
        $usuarioModel->actualizarUsuario($id, $data);
        session()->setFlashdata('success', 'Usuario baneado.');
        return $this->response->redirect(site_url('/usuarios'));
    }

    public function activarUsuario($id)
    {
        $usuarioModel = new Usuarios_model();
        $data = ['baja' => 'NO'];
        $usuarioModel->actualizarUsuario($id, $data);
        session()->setFlashdata('success', 'Usuario activado exitosamente.');
        return $this->response->redirect(site_url('/baneados'));
    }

    /* MANEJAMOS LAS CONSULTAS */
    public function listar_consultas(){
        $consultas = new consulta_model();
        $data['consultas'] = $consultas->getConsultas();
        $dato['titulo'] = 'Gestion-Consultas';

        echo view('front/header_view', $dato);
        echo view('front/nav_view');
        echo view('back/consultas/listar_consultas', $data);
        echo view('front/footer_view');
    }

    public function atender_consulta($id = null){
        $consultasM = new consulta_model();
        $consultasM->getConsulta($id);
        $consultasM->update($id, ['respuesta' => 'SI']);
        return redirect()->to(base_url('listar_consultas'));
    }

    public function eliminar_consulta($id = null){
        $model = new consulta_model();
        $model->getConsulta($id);
        $model->delete($id);

        return redirect()->to(base_url('listar_consultas'));
    }

    // Método para mostrar el formulario de contacto (adaptado para usuarios logueados y no logueados)
    public function Contact(){
        $data = []; // Inicializamos data vacía.

        $dato['titulo']='Informacion de Contacto';
        echo view('front/header_view', $dato);
        echo view('front/nav_view');
        // Es crucial pasar el servicio de validación a la vista.
        echo view('front/info_Contact', array_merge($data, ['validation' => \Config\Services::validation()]));
        echo view('front/footer_view');
    }

    public function consultasValidation(){
        $input = $this->validate([
            'nombre'    => 'required|min_length[3]',
            'apellido'  => 'required|min_length[3]|max_length[50]',
            'email'     => 'required|min_length[4]|max_length[100]|valid_email', 
            'telefono'  => 'required|min_length[4]|max_length[35]',
            'mensaje'   => 'required|min_length[5]|max_length[250]'
        ]);

        $consulta = new consulta_model();

        if (!$input) {
            // Si la validación falla, redirigimos de vuelta con los datos del POST
            // y los errores de validación. CodeIgniter usa esto para set_value().
            return redirect()->back()->withInput()->with('validation', $this->validator);
        } else {
            // Si la validación es exitosa, guardamos la consulta.
            $consulta->save([
                'nombre'    => $this->request->getVar('nombre'),
                'apellido'  => $this->request->getVar('apellido'),
                'email'     => $this->request->getVar('email'),
                'telefono'  => $this->request->getVar('telefono'),
                'mensaje'   => $this->request->getVar('mensaje'),
                'respuesta' => 'NO' // Valor por defecto para el campo 'respuesta'
            ]);
            session()->setFlashdata('succes', 'Consulta registrada con exito');
            return redirect()->to(base_url('Casa')); // Redirige a la página de inicio o confirmación
        }
    }
}



