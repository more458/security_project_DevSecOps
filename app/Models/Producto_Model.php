<?php
namespace App\Models;
use CodeIgniter\Model;

class Producto_Model extends Model {
    protected $table = 'productos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre_prod', 'imagen', 'categoria_id', 'precio', 'precio_vta', 'stock', 'stock_min', 'eliminado'];

    protected $returnType = 'object'; // Asegura que los resultados sean objetos

    public function getBuilderProductos(){
        // conect() es un metodo de la clase Database, que nos permite conectar a la base de datos
        $db = \Config\Database::connect();
        // $builders es una instancia de la clase QueryBuilder de Codefigniter
        $builder = $db->table('productos');
        // hace una consulta a la base de datos
        //$builder->select('*');
        $builder->select(
            'productos.id AS producto_id,
            productos.nombre_prod,
            productos.imagen,
            productos.precio,
            productos.precio_vta,
            productos.stock,
            productos.stock_min,
            productos.eliminado,
            categorias.descripcion AS categoria_descripcion'
        );
        // hago el join de la tabla categoria
        $builder->join('categorias', 'categorias.id = productos.categoria_id');
        // retorna el builder
        return $builder;
    }

    public function getProductoAll($id = null){
    /*   $builder = $this->getBuilderProductos();
        $builder->where('productos.id', $id);
        $query = $builder->get();
        return $query->getRowArray();*/
        $builder = $this->getBuilderProductos();
        $builder->where('eliminado', 'NO');

        if ($id !== null) {
            $builder->where('productos.id', $id);
            $query = $builder->get();
            return $query->getRow(); // uno solo
        } else {
            $query = $builder->get();
            return $query->getResult(); // muchos productos
            
        }
    }

    // --- NUEVO MÉTODO PARA EL CARRITO ---
    public function getProductoById($id)
    {
        $builder = $this->getBuilderProductos();
        $builder->where('productos.id', $id);
        $builder->where('productos.eliminado', 'NO'); // Asegura que el producto no esté eliminado
        $query = $builder->get();
        return $query->getRow(); // Devolvemos un solo objeto
    }
    // --- FIN NUEVO MÉTODO ---

    public function updateStock($id = null, $stock_actual = null){
        $builder = $this->getBuilderProductos();
        $builder->where('productos.id', $id);
        $builder->set('productos.stock', $stock_actual);
        $builder->update();
    }

    //este metodo esta creado por tu servidor
    public function getProductoElimAll($id = null) {
    $builder = $this->getBuilderProductos();
    $builder->where('eliminado', 'SI'); // Filtra solo productos eliminados

        if ($id !== null) {
            $builder->where('productos.id', $id);
            $query = $builder->get();
            return $query->getRow(); // uno solo
        } else {
            $query = $builder->get();
            return $query->getResult(); // muchos productos
        }
    }
}