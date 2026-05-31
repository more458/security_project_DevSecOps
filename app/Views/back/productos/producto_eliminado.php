<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <div>
            <a href="<?php echo base_url('crear');?>" class="btn btn-success">Volver</a>
        </div>
        <input type="text" class="form-control w-25" placeholder="Search..." id="searchInput">
    </div>
    <table class="table table-bordered table-striped text-center">
        <thead class="thead-light">
            <tr>
                <th>Id</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Precio Vta</th>
                <th>Stock</th>
                <th>Imagen</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productosElim as $producto): ?>
                <tr>
                    <td><?= $producto->producto_id ?></td><!-- se le pasa el ide del producto pero mustra el ide de la categoria corregir -->
                    <td><?= $producto->nombre_prod ?></td>
                    <td><?= number_format($producto->precio, 2) ?></td>
                    <td><?= number_format($producto->precio_vta, 2) ?></td>
                    <td><?= $producto->stock ?></td>
                    <td>
                        <img src="<?= base_url('assets/uploads/' . $producto->imagen) ?>" width="60" height="60">
                    </td>
                    <td>
                        <a href="<?= site_url('activar_pro/' . $producto->producto_id ) ?>" class="btn btn-secondary btn-sm">Activar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>