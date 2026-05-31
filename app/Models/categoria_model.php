<?php
namespace App\Models;
use CodeIgniter\Model;

class categoria_model extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id';
    protected $allowedFields = ['descripcion', 'activo'];

    protected $returnType = 'object';//asi manejamos objetos 
    
    public function getCategorias()
    {
        return $this->findAll();
    }
}
