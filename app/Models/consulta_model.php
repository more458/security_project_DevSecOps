<?php 
namespace App\Models;

use CodeIgniter\Model;

class consulta_model extends Model {
    protected $table = 'consultas'; 
    protected $primaryKey = 'id_consulta';
    protected $allowedFields = [
        'nombre', 
        'apellido', 
        'email', 
        'telefono', 
        'mensaje', 
        'respuesta' 
    ];
    //protected $returnType = 'object';

    /*TODAS LAS CONSULTAS*/
    public function getConsultas()
    {
        return $this->findAll(); 
    }

    /*POR ID*/
    public function getConsulta($id)
    {
        return $this->where('id_consulta', $id)->first();
    }

    //ATENDEMOS
    public function atenderConsulta($id, $respuesta)
    {
        return $this->update($id, ['respuesta' => $respuesta]);
    }

}