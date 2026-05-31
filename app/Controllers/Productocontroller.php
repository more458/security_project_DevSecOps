<?php
namespace App\Controllers;
use App\Models\Producto_Model;
use App\Models\Usuarios_Model; // Asegúrate de que estos modelos existan y sean necesarios
use App\Models\Ventas_cabecera_model; // Asegúrate de que estos modelos existan y sean necesarios
use App\Models\Ventas_detalle_model; // Asegúrate de que estos modelos existan y sean necesarios
use App\Models\categoria_model;
use CodeIgniter\Controller;

class Productocontroller extends Controller
{
    public function __construct(){
        helper(['url', 'form']);
        // service('session'); // CodeIgniter suele cargar la sesión automáticamente, pero si tienes problemas, puedes descomentar esto.
    }

    // mostrar los productos en lista
    public function index()
    {
        $productoModel = new Producto_Model();
        $data['productos'] = $productoModel->getProductoAll();

        $dato['titulo']='Crud_productos';
        echo view('front/header_view', $dato);
        echo view('front/nav_view');
        echo view('back/productos/producto_nuevo_view', $data);
        echo view('front/footer_view');
    }

    public function creaproducto(){
        $categoriasmodel = new categoria_model();
        $data['categorias'] = $categoriasmodel->getCategorias(); // traer las categorias desde la db

        // No es necesario cargar todos los productos aquí, a menos que tu form_alta también los muestre.
        // $productoModel = new Producto_Model();
        // $data['productos'] = $productoModel->orderBy('id', 'DESC')->findAll();

        $dato['titulo']='Alta producto';
        echo view('front/header_view', $dato);
        echo view('front/nav_view');
        echo view('back/productos/form_alta', $data);
        echo view('front/footer_view');
    }

    public function store() {
        // Construimos las reglas de validación
        $input = $this->validate([
            'nombre_prod' => 'required|min_length[3]',
            'categoria_id' => 'required|is_not_unique[categorias.id]', // CAMBIO: Usar 'categoria_id' si es el name del select, y 'required'
            'precio' => 'required|numeric',
            'precio_vta' => 'required|numeric',
            'stock' => 'required|integer', // CAMBIO: 'integer' para stock
            'stock_min' => 'required|integer', // CAMBIO: 'integer' para stock_min
            'imagen' => 'uploaded[imagen]|max_size[imagen,2048]|is_image[imagen]|mime_in[imagen,image/jpg,image/jpeg,image/png]' // CAMBIO: Añadido max_size, is_image, mime_in
        ]);

        $productoModel = new Producto_Model(); // se instancia el modelo

        if (!$input) {
            $categoria_model = new categoria_model();
            $data['categorias'] = $categoria_model->getCategorias();
            $data['validation'] = $this->validator; // Pasa el objeto validador a la vista

            $dato['titulo'] = 'Alta producto'; // CAMBIO: Título consistente
            echo view('front/header_view', $dato);
            echo view('front/nav_view');
            echo view('back/productos/form_alta', $data);
            echo view('front/footer_view');
            return; // Importante: Detener la ejecución aquí si la validación falla
        }

        $img = $this->request->getFile('imagen');
        $nombre_aleatorio = $img->getRandomName();
        // CAMBIO: Usar FCPATH para la ruta del directorio público
        $img->move(FCPATH . 'assets/img/productos', $nombre_aleatorio); // CAMBIO: Mover a la carpeta de imágenes de productos

        $data = [
            'nombre_prod' => $this->request->getVar('nombre_prod'),
            'imagen' => $nombre_aleatorio, // CAMBIO: Guardar el nombre_aleatorio, no $img->getName() que podría ser el original
            'categoria_id' => $this->request->getVar('categoria_id'), // CAMBIO: Usar 'categoria_id'
            'precio' => $this->request->getVar('precio'),
            'precio_vta' => $this->request->getVar('precio_vta'),
            'stock' => $this->request->getVar('stock'),
            'stock_min' => $this->request->getVar('stock_min'),
            'eliminado' => 'NO', // CAMBIO: Asegúrate de que se inserte como 'NO' por defecto
        ];

        $productoModel->insert($data); // CAMBIO: Usa el mismo modelo instanciado arriba
        session()->setFlashdata('success', 'Producto dado de alta exitosamente.'); // CAMBIO: Mensaje más específico
        return $this->response->redirect(site_url('crear')); // CAMBIO: Redirigir a la lista de productos
    }

    // Muestra el formulario para editar un solo producto
    public function singleproducto($id = null){
        $productoModel = new Producto_Model();
        // Usamos getProductoById para asegurarnos de que cargamos un objeto con el alias 'producto_id'
        $data['old'] = $productoModel->getProductoById($id);

        if (empty($data['old'])){
            // Lanzar una excepción de página no encontrada es una buena práctica
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No se ha seleccionado un producto para editar.');
        }

        // Instancio el modelo de categorías
        $categoriasModel = new categoria_model(); // CAMBIO: Nombre de variable más claro
        $data['categorias'] = $categoriasModel->getCategorias(); // Traigo las categorías desde la DB

        $data['titulo']='Editar Producto'; // CAMBIO: Título más apropiado para la vista de edición
        echo view('front/header_view', $data);
        echo view('front/nav_view');
        echo view('back/productos/edit', $data); // Carga la vista de edición
        echo view('front/footer_view');
    }

    // Procesa el formulario de edición y actualiza el producto
    public function modified($id){
        $productoModel = new Producto_Model();
        // Obtener el producto actual de la BD para validaciones y eliminar la imagen antigua
        $prod = $productoModel->getProductoById($id); // Usamos getProductoById para consistencia

        if (empty($prod)) {
            session()->setFlashdata('error', 'Producto no encontrado para editar.');
            return redirect()->to(base_url('crear'));
        }

        // Validación de los datos del formulario
        $input = $this->validate([
            'nombre_prod' => 'required|min_length[3]',
            'categoria_id' => 'required|is_not_unique[categorias.id]', // CAMBIO: 'categoria_id' y 'required'
            'precio' => 'required|numeric',
            'precio_vta' => 'required|numeric',
            'stock' => 'required|integer',
            'stock_min' => 'required|integer',
            'imagen' => 'max_size[imagen,2048]|is_image[imagen]|mime_in[imagen,image/jpg,image/jpeg,image/png]' // Opcional para editar: imagen no es 'uploaded'
        ]);

        if (!$input) {
            // Si la validación falla, recarga el formulario con los errores y los datos antiguos
            $categoriasModel = new categoria_model();
            $data['categorias'] = $categoriasModel->getCategorias();
            $data['old'] = $prod; // Pasamos los datos del producto original
            $data['validation'] = $this->validator; // Pasa el objeto validador
            $data['titulo'] = 'Editar Producto';

            echo view('front/header_view', $data);
            echo view('front/nav_view');
            echo view('back/productos/edit', $data); // Vuelve a cargar la vista de edición con errores
            echo view('front/footer_view');
            return; // Detiene la ejecución aquí
        }

        $dataToUpdate = [ // Usamos un array de datos para la actualización
            'nombre_prod' => $this->request->getVar('nombre_prod'),
            'categoria_id' => $this->request->getVar('categoria_id'), // CAMBIO: Usar 'categoria_id'
            'precio' => $this->request->getVar('precio'),
            'precio_vta' => $this->request->getVar('precio_vta'),
            'stock' => $this->request->getVar('stock'),
            'stock_min' => $this->request->getVar('stock_min'),
            // 'eliminado' se maneja por separado en deleteproducto/activarproducto
        ];

        $img = $this->request->getFile('imagen');

        // Verifica si se cargó un archivo de imagen válido y si no es un error de carga
        if ($img && $img->isValid() && !$img->hasMoved()) {
            // CAMBIO: Borrar la imagen anterior si existe y no es la imagen por defecto
            $oldImagePath = FCPATH . 'assets/img/productos/producto_' . $prod->producto_id . '.jpg';
            // CAMBIO: Asegúrate de que 'default.jpg' sea el nombre de tu imagen por defecto y no se borre
            if (file_exists($oldImagePath) && basename($oldImagePath) !== 'default.jpg') {
                unlink($oldImagePath); // Elimina el archivo físico de la imagen anterior
            }

            // Mueve la nueva imagen con el ID del producto como nombre
            $newImageName = 'producto_' . $id . '.jpg'; // CAMBIO: Nombra la imagen con el ID del producto
            $img->move(FCPATH . 'assets/img/productos', $newImageName);
            $dataToUpdate['imagen'] = $newImageName; // Actualiza el campo 'imagen' con el nuevo nombre
        }
        // Si no se cargó una nueva imagen, 'imagen' no se incluirá en $dataToUpdate, manteniendo la existente en la BD.

        $productoModel->update($id, $dataToUpdate); // Actualiza el producto en la base de datos
        session()->setFlashdata('success', 'Producto actualizado exitosamente.');
        return redirect()->to(base_url('crear')); // Redirige a la lista de productos
    }

    //eliminar logicamente
    public function deleteproducto($id)
    {
        $productoModel = new Producto_Model();
        // CAMBIO: Solo necesitamos actualizar el campo 'eliminado', no recuperar todo el objeto si no es necesario
        $data = ['eliminado' => 'SI']; // Array directamente con el campo a actualizar
        $productoModel->update($id, $data);
        session()->setFlashdata('success', 'Producto eliminado lógicamente.'); // Mensaje de éxito
        return $this->response->redirect(site_url('crear')); // Redirigir a la lista de productos
    }

    public function eliminados()
    {
        $productoModel = new Producto_Model();
        $data['productosElim'] = $productoModel->getProductoElimAll();

        $data['titulo']='Productos Eliminados'; // CAMBIO: Título más descriptivo
        echo view('front/header_view', $data);
        echo view('front/nav_view');
        echo view('back/productos/producto_eliminado', $data);
        echo view('front/footer_view');
    }

    public function activarproducto($id)
    {
        $productoModel = new Producto_Model();
        // CAMBIO: Solo necesitamos actualizar el campo 'eliminado'
        $data = ['eliminado' => 'NO']; // Array directamente con el campo a actualizar
        $productoModel->update($id, $data);
        session()->setFlashdata('success', 'Producto activado exitosamente.');
        return $this->response->redirect(site_url('eliminados')); // CAMBIO: Redirigir a la lista de productos activos
    }


    public function MostrarCatalogo()
    {
        $productoModel = new Producto_Model();
        $categoriaModel = new categoria_model(); // instanciamos 

        // obtiene id de categoría segun lo que se selecciona
        $categoriaId = $this->request->getVar('categoria_id');

        // todas las categorías para el filtro 
        $data['categorias'] = $categoriaModel->getCategorias();

        // filtrado
        if (!empty($categoriaId)) {
            $data['productosCat'] = $productoModel->where('categoria_id', $categoriaId)
                                                 ->where('eliminado', 'NO')
                                                 ->findAll();
            $data['selectedCategory'] = $categoriaId; // mantenemos seleciuonado
        } else {
            // muestra todo sin filtrar
            $data['productosCat'] = $productoModel->where('eliminado', 'NO')->findAll();
        }

        $dato['titulo']='Catálogo de Productos'; 
        echo view('front/header_view', $dato);
        echo view('front/nav_view');
        echo view('back/productos/Catalogo_Productos', $data); 
        echo view('front/footer_view');
    }

}
