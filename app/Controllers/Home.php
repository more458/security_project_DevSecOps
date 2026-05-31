<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends BaseController
{
    public function index()
    {
        $tituloVar['titulo'] = 'principal';
        echo view('front/header_view', $tituloVar);
        echo view('front/nav_view');
        echo view('front/plantilla');
        echo view('front/footer_view');
    }

    public function quienes_somos()
    {
        $tituloVar['titulo']='quienes somos';
        echo view ('front/header_view',$tituloVar);
        echo view ('front/nav_view');
        echo view ('front/quienesSomos');
        echo view ('front/footer_view');
    }

    public function Comercializacion()
    {
        $tituloVar['titulo']='Comercializacion';
        echo view ('front/header_view',$tituloVar);
        echo view ('front/nav_view');
        echo view ('front/Comercializacion');
        echo view ('front/footer_view');
    }

    public function terminosUso(){
        $tituloVar['titulo']='Comercializacion';
        echo view ('front/header_view',$tituloVar);
        echo view ('front/nav_view');
        echo view ('front/Terminos_Uso');
        echo view ('front/footer_view');  
    }


    
    public function Contact()
    {
        $tituloVar['titulo']='Comercializacion';
        echo view ('front/header_view',$tituloVar);
        echo view ('front/nav_view');
        echo view ('front/Info_Contact');
        echo view ('front/footer_view');  
    }

    public function login()
    {
        $tituloVar['titulo']='Iniciar Sesion';
        echo view ('front/header_view',$tituloVar);
        echo view ('front/nav_view');
        echo view ('back/usuario/login');
        echo view ('front/footer_view');
    }

    public function denegado()
    {
        $tituloVar['titulo']='Acceso Denegado';
        echo view ('front/header_view',$tituloVar);
        echo view ('front/nav_view');
        echo view ('back/filtros/denegado');
        echo view ('front/footer_view');
    }
    

    

}
