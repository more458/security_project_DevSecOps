<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Producto_model;
use App\Models\Usuarios_model;
use App\Models\Ventas_cabecera_model;
use App\Models\Ventas_detalle_model;

class Ventas_controller extends Controller{

    public function registrar_venta()
    {
        $session = session();
        require(APPPATH . 'Controllers/CarritoController.php');
        $cartController = new CarritoController(); //instancia
        $carrito_contents = $cartController->devolver_carrito();

        $productModel = new Producto_model();
        $ventasModel = new Ventas_cabecera_model();
        $detalleModel = new Ventas_detalle_model();

        $productos_validos = [];
        $productos_sin_stock = [];
        $total = 0;

        // Validar stock y filtrar productos válidos
        foreach ($carrito_contents as $item) {
            $producto = $productModel->getProductoById($item['producto_id']);
        
            if ($producto && $producto->stock >= $item['quantity']) {
                $productos_validos[] = $item;
                $total += $item['precio_vta'] * $item['quantity'];
            } else {
                $productos_sin_stock[] = $item['nombre_prod'];
                // Eliminar del carrito el producto sin stock
                $cartController->eliminar_item($item['producto_id']);
            }
        }

        // Si hay productos sin stock, avisar y volver al carrito
        if (!empty($productos_sin_stock)) {
            $mensaje = 'Los siguientes productos no tienen stock suficiente y fueron eliminados del carrito: <br>'
                . implode(', ', $productos_sin_stock);
            $session->setFlashdata('mensaje', $mensaje);
            return redirect()->to(base_url('carrito'));
        }





        //aca hay un error
        
        // Si no hay productos válidos, no se registra la venta
        if (empty($productos_validos)) {
            $session->setFlashdata('mensaje', 'No hay productos válidos para registrar la venta.');
            return redirect()->to(base_url('Casa'));
        }


        // Si hay productos sin stock, avisar y volver al carrito
        if (!empty($productos_sin_stock)) {
            $mensaje = 'Los siguientes productos no tienen stock suficiente y fueron eliminados del carrito: <br>'
                . implode(', ', $productos_sin_stock);
            $session->setFlashdata('mensaje', $mensaje);
            return redirect()->to(base_url('carrito'));
        }

        // Si no hay productos válidos, no se registra la venta
        if (empty($productos_validos)) {
            $session->setFlashdata('mensaje', 'No hay productos válidos para registrar la venta.');
            return redirect()->to(base_url('carrito'));
        }

        // Registrar cabecera de la venta
        $nueva_venta = [
            'usuario_id' => $session->get('id_usuario'),
            'total_venta' => $total
        ];
        $venta_id = $ventasModel->insert($nueva_venta);

        // Registrar detalle y actualizar stock
        foreach ($productos_validos as $item) {
            $detalle = [
                'venta_id' => $venta_id,
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['quantity'],
                'precio' => $item['precio_vta']
            ];
            $detalleModel->insert($detalle);
        
            $producto = $productModel->getProductoById($item['producto_id']);
            $productModel->updateStock($item['producto_id'], $producto->stock - $item['quantity']);
        }

        // Vaciar carrito y mostrar confirmación
        $cartController->vaciar();
        $session->setFlashdata('mensaje', 'Venta registrada exitosamente.');
        //fijate porque no carga el css y pasa lo mismo con edit
        return redirect()->to(base_url('/facturitas/' . $venta_id));
        

    }

    public function ver_factura($venta_id)
    {
        //echo $venta_id;die;
        $detalle_ventas = new Ventas_detalle_model();
        $data['venta'] = $detalle_ventas->getBuilderDetalles($venta_id);
    
        $dato['titulo'] = "Mi compra";
    
        echo view('front/header_view',$dato);
        echo view('front/nav_view');
        echo view('back/compras/vista_compras',$data);
        echo view('front/footer_view');
    }
    
    //función del cliente para ver el detalle de su facturas de compras
    public function ver_facturas_usuario($id_usuario){
    
        $ventas = new ventas_cabecera_model;
    
        $data['ventas'] = $ventas->getVentas($id_usuario);
        $dato['titulo'] = "Todos mis compras";
    
        echo view('front/header_view',$dato);
        echo view('front/nav_view');
        echo view('back/compras/ver_factura_usuario',$data);
        echo view('front/footer_view');
    }

    /*funcion para que el administrador vea las ventas*/
    public function ventas(){

        $venta_id = $this->request->getGet('id');
        //echo $venta_id;die;
        $detalle_ventas = new Ventas_detalle_model();
        $data['venta'] = $detalle_ventas->getBuilderDetalles($venta_id);
    
        $ventascabecera = new Ventas_cabecera_model();
        $data['usuarios']=$ventascabecera->getBuilderCabecera();
    
        $dato['titulo'] = "ventas";
        echo view('front/header_view',$dato);
        echo view('front/nav_view');
        echo view('back/compras/ventas',$data);
        echo view('front/footer_view');
    }
    

        

}

