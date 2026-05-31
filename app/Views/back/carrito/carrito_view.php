<div class="container mt-5">
    <h2>Mi Carrito de Compras</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!--CARRITO VACIO-->
    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info">Tu carrito está vacío. ¡Empieza a añadir productos!</div>
        <a href="<?= base_url('/') ?>" class="btn btn-primary">Volver al catálogo</a>
    <?php else: ?><!--CARRITO-->
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Imagen</th>
                        <th>Precio Unit.</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= esc($item['nombre_prod']) ?></td>
                            <td>
                                <?php
                                //$imagenPath = 'assets/uploads/'. $item->imagen .'.jpg';
                                $nombreImagen = isset($item['imagen']) ? $item['imagen'] : ''; 
                                $imagenPath = 'assets/uploads/' . $nombreImagen; // ...
                                $imagenFinal = (isset($item['imagen']) && $item['imagen'] !== '' && file_exists(FCPATH . $imagenPath)) ? $imagenPath : $imagenDefault;
                                $imagenDefault = 'assets/img/productos/default.jpg';
                                $imagenFinal = file_exists(FCPATH.$imagenPath) ? $imagenPath : $imagenDefault;
                                ?>
                                <img src="<?= base_url($imagenFinal) ?>" alt="<?= esc($item['nombre_prod']) ?>" style="width: 70px; height: 70px; object-fit: contain;">
                            </td>
                            <td>$<?= number_format($item['precio_vta'], 2) ?></td>
                            <td>
                                <form action="<?= site_url('carrito/actualizar') ?>" method="post" class="d-flex align-items-center">
                                    <input type="hidden" name="producto_id" value="<?= $item['producto_id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" class="form-control text-center me-2" style="width: 80px;" onchange="this.form.submit()">
                                    <button type="submit" class="btn btn-sm btn-outline-primary" style="display: none;">Actualizar</button> </form>
                            </td>
                            <td>$<?= number_format($item['precio_vta'] * $item['quantity'], 2) ?></td>
                            <td>
                                <a href="<?= site_url('carrito/eliminar/' . $item['producto_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto del carrito?');">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                        <?php $total += ($item['precio_vta'] * $item['quantity']); ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total:</th>
                        <th colspan="2">$<?= number_format($total, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!--BOTONES-->
        <div class="d-flex justify-content-between mt-4">
            <a href="<?= base_url('/') ?>" class="btn btn-secondary">Seguir Comprando</a><!--SEGUIMOS COMPRANDO-->
            <div>
                <a href="<?= site_url('carrito/vaciar') ?>" class="btn btn-warning me-2" onclick="return confirm('¿Estás seguro de vaciar todo el carrito?');">
                    <i class="fas fa-trash"></i> Vaciar Carrito 
                </a><!--VACIAR-->
                <a href="<?= site_url('/comprar') ?>" class="btn btn-success">
                    <i class="fas fa-money-check-alt"></i> Proceder al Pago
                </a><!--PAGO-->
            </div>
        </div>
    <?php endif; ?>
</div>