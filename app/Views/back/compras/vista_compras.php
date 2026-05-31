<?php
$session=session();
if (empty($venta)) : ?>
    <!-- avisamos que no hay consultas -->
    <div class="alert alert-dark text-center" role="alert">
        <h4 class="alert-heading">No posee compras registradas</h4>
        <p>Para realizar una compra visite nuestro catálogo.</p>
        <hr>
        <a class="btn btn-warning my-2 w-10" href="<?php echo base_url('catalogo') ?>">Catalogo</a>
    </div>
<?php endif; ?>

<!-- Mostrar mensaje Flash si existe -->
<?php if (session()->getFlashdata('mensaje')) : ?>
    <div class="alert alert-warning alert-dismissible fade show mt-3 mx-3" role="alert">
        <?= session()->getFlashdata("mensaje") ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
<?php endif; ?>
<div class="row">
    <div class="container">
        <div class="col-xl-12 col-xs-10">
            <table class="table table-secondary table-responsive table-bordered table-striped rounded">
            <thead>
                <tr class="text-center">
                    <th>N° ORDEN</th>
                    <th>NOMBRE PRODUCTO</th>
                    <th>IMAGEN</th>
                    <th>CANTIDAD</th>
                    <th>COSTO</th>
                    <th>COSTO SUB-TOTAL</th>
                </tr>
            </thead>
            <tbody>
        <?php $i = 0;
        $total = 0;
        ?>
        <!-- Si es array de ventas y no está vacío -->
          <?php if (!empty($venta) && is_array($venta)) :
            foreach ($venta as $row) {
              $imagen = $row['imagen'];
              // $total = $row['precio'];
              $i++; ?>
              <tr class="text-center">
                <td><?php echo $i ?></td>
                <td><?php echo $row['nombre_prod'] ?></td>
                <td><img width="100" height="65" src="<?php echo base_url('assets/uploads/'.$imagen) ?>"></td>
                <td><?php echo number_format($row['cantidad']) ?></td>
                <td><?php echo $row['precio_vta'] ?></td>
                <td>
                  <?php $subtotal= ($row['precio_vta'] * $row['cantidad']);
                  echo "$" . number_format($subtotal, 2); ?>
                </td>
              </tr>
          <?php
              $total+=$subtotal;
            }
          endif; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" class="text-right">
              <h5>Total</h5>
            </td>
            <td colspan="6" class="text-right">
              <h5><?php echo number_format($total, 2) ?></h5>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
  <div class="row"><!--mensajito-->
    <div class="col-xl-12 col-xs-12 text-center">
      <h5 class="text-warning">Gracias por su compra!</h5>
    </div>
    </div>
<?php ?>