<?php
namespace App\Models;
use CodeIgniter\Model;

class Usuarios_model extends Model
{
    protected $table = 'perfiles';
    protected $primaryKey = 'id_perfiles';
    protected $allowedFields = ['descripcion', 'id_usuario'];

    // Método para agregar un usuario
    public function agregarPerfil($data)
    {
        return $this->insert($data);
    }

    // Método para obtener datos de un usuario para editar (por id)
    public function obtenerPerfil($id)
    {
        return $this->find($id);
    }

    // Método para actualizar un usuario
    public function actualizarPerfiles($id, $data)
    {
        return $this->update($id, $data);
    }

    // Método para borrar un usuario
    public function borrarPerfil($id)
    {
        return $this->delete($id);
    }
}