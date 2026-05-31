<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/Login');
        }

        // Verificación de perfil según ID
        if ($arguments) {
            $perfilID = session()->get('perfil_id'); // Suponemos que guardaste esto al loguear

            // Convertimos los argumentos a int para comparar bien
            $perfilesPermitidos = array_map('intval', $arguments);

            if (!in_array((int) $perfilID, $perfilesPermitidos)) {
                return redirect()->to('/AccesoDenegado');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se necesita lógica por ahora
    }
}
