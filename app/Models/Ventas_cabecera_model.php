<?php
namespace App\Models;
use CodeIgniter\Model;

class Ventas_cabecera_model extends Model{
    protected $table = 'ventas_cabecera';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id','fecha','usuario_id','total_venta'];


    public function getBuilderCabecera($id = null, $id_usuario = null){
        $db = \Config\Database::connect();
        $builder = $db->table('ventas_cabecera');
        $builder->select('*');
        $builder->join('usuarios', 'usuarios.id_usuario = ventas_cabecera.usuario_id');
        
        
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getVentas($id_usuario = null){
        if ($id_usuario == null){
            //Si el $id_usuario es null
            //La función getBuilderVentas_cabecera() devuelve el resultado de la consulta como array.
            return $this->getBuilderCabecera();
        } else {
            $db = \Config\Database::connect();
            $builder = $db->table('ventas_cabecera');
            $builder->select("*");
            $builder->join('usuarios', 'usuarios.id_usuario = ventas_cabecera.usuario_id');
            $builder->where('ventas_cabecera.usuario_id', $id_usuario);
            $query = $builder->get();
            return $query->getResultArray();
        }
    }
}