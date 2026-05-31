<?php
namespace App\Models;
use CodeIgniter\Model;

class Usuarios_model extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    protected $allowedFields = ['nombre', 'apellido', 'usuario', 'email', 'pass', 'perfil_id', 'baja'];

    public function getBuilderUsuario(){
        $db = \Config\Database::connect();
        $builder = $db->table('usuarios');
        $builder->select('*');
        $builder->join('perfiles', 'perfiles.id_perfiles = usuarios.perfil_id');
        
        
        return $builder;
    }

    public function getUsuarioElimAll($id = null) {
    $builder = $this->getBuilderUsuario();
    $builder->where('baja', 'SI'); // Filtra solo productos eliminados

        if ($id !== null) {
            $builder->where('id_usuario', $id);
            $query = $builder->get();
            return $query->getRow(); // uno solo
        } else {
            $query = $builder->get();
            return $query->getResult(); // muchos productos
        }
    }

    

    public function getUsuarioAll($id = null) {
    $builder = $this->getBuilderUsuario();
    $builder->where('baja', 'NO'); // Filtra solo productos eliminados

        if ($id !== null) {
            $builder->where('id_usuario', $id);
            $query = $builder->get();
            return $query->getRow(); // uno solo
        } else {
            $query = $builder->get();
            return $query->getResult(); // muchos productos
        }
    }


    // Método para agregar un usuario
    public function agregarUsuario($data)
    {
        return $this->insert($data);
    }

    // Método para obtener datos de un usuario para editar (por id)
    public function obtenerUsuario($id)
    {
        return $this->find($id);
    }

    // Método para actualizar un usuario
    public function actualizarUsuario($id, $data)
    {
        return $this->update($id, $data);
    }

    // Método para borrar un usuario
    public function borrarUsuario($id)
    {
        return $this->delete($id);
    }
}
