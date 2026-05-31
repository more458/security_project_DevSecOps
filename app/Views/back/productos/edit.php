<!--ACTUALIZACION 9/6/25-->
<div class="container mt-1 mb-1 d-flex justify-content-center">
    <div class="card" style="width:75%;">
        <div class="card-header text-center">
            <h2>Editar Productos</h2>
        </div>
        <?php if (!empty(session()->getFlashdata('fail'))): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('fail'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty(session()->getFlashdata('success'))): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php $validation = \Config\Services::validation(); ?>

        <?php
        //OBTENEMOS CON OLD COMO OBJETO
        $id_producto = $old->producto_id ?? null;
        $nombre_prod_old = $old->nombre_prod ?? '';
        $categoria_id_old = $old->categoria_id ?? null;
        $precio_old = $old->precio ?? '';
        $precio_vta_old = $old->precio_vta ?? '';
        $stock_old = $old->stock ?? '';
        $stock_min_old = $old->stock_min ?? '';
        $imagen_old = $old->imagen ?? '';
        ?>

        <form action="<?= base_url('modified/' . $id_producto) ?>" method="post" enctype="multipart/form-data">
            <div class="card-body" media="(max-width:568px)">

                <!--EDITAMOS EL NOMBRE -->
                <div class="mb-2">
                    <label for="nombre_prod" class="form-label">Producto</label>
                    <input class="form-control" type="text" name="nombre_prod" id="nombre_prod"
                           value="<?= set_value('nombre_prod', $nombre_prod_old); ?>"
                           placeholder="Nombre del producto" autofocus>

                    <?php if ($validation->hasError('nombre_prod')): ?>
                        <div class="alert alert-danger mt-2">
                            <?= $validation->getError('nombre_prod'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!--EDITAMOS LA CATEGORIA -->
                <div>
                    <label class="mt-2" for="categoria_id">Categoría</label>
                    <select class="form-control" name="categoria_id" id="categoria_id">
                        <option value="">Seleccionar Categoría</option>
                        <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?= $categoria['id']; ?>"
                                <?= set_select('categoria_id', $categoria['id'], ($categoria['id'] == $categoria_id_old)); ?>>
                                <?= esc($categoria['descripcion']); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <?php if ($validation->hasError('categoria_id')): ?>
                        <div class='alert alert-danger mt-2'>
                            <?= $validation->getError('categoria_id'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!--OBTENEMOS EL PRECIO DE COSTO ANTERIOR O ACTUAL Y EDITAMOS EN CASO DE NECESITARLO -->
                <div class="mb-2">
                    <label for="precio" class="form-label">Precio de Costo</label>
                    <input class="form-control" type="text" name="precio" id="precio"
                           value="<?= set_value('precio', $precio_old); ?>">
                    <?php if ($validation->hasError('precio')): ?>
                        <div class="alert alert-danger mt-2">
                            <?= $validation->getError('precio'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!--OBTENEMOS EL PRECIO DE VENTA ANTERIOR Y EDITAMOS EN CASO DE NECESITARLO -->
                <div class="mb-2">
                    <label for="precio_vta" class="form-label">Precio de Venta</label>
                    <input class="form-control" type="text" name="precio_vta" id="precio_vta"
                           value="<?= set_value('precio_vta', $precio_vta_old); ?>">
                    <?php if ($validation->hasError('precio_vta')): ?>
                        <div class="alert alert-danger mt-2">
                            <?= $validation->getError('precio_vta'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!--OBTENEMOS EL STOCK ANTERIOR Y EDITAMOS EN CASO DE NECESITARLO -->
                <div class="mb-2">
                    <label for="stock" class="form-label">Stock</label>
                    <input class="form-control" type="text" name="stock" id="stock"
                           value="<?= set_value('stock', $stock_old); ?>">
                    <?php if ($validation->hasError('stock')): ?>
                        <div class="alert alert-danger mt-2">
                            <?= $validation->getError('stock'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!--OBTENEMOS EL STOCK MINIMO ANTERIOR Y EDITAMOS EN CASO DE NECESITARLO -->
                <div class="mb-2">
                    <label for="stock_min" class="form-label">Stock Mínimo</label>
                    <input class="form-control" type="text" name="stock_min" id="stock_min"
                           value="<?= set_value('stock_min', $stock_min_old); ?>">
                    <?php if ($validation->hasError('stock_min')): ?>
                        <div class="alert alert-danger mt-2">
                            <?= $validation->getError('stock_min'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!--MANEJAMOS IMAGEN ANTERIOR Y ACTUAL EN CASO DE EDITAR-->
                <div class="mb-2">
                    <label for="imagen" class="form-label">Imagen</label>
                    <input class="form-control" type="file" name="imagen" id="imagen" accept="image/png, image/jpg, image/jpeg">
                    <?php if (!empty($imagen_old)): ?>
                        <div class="mt-2">
                            <p>Imagen actual:</p>
                            <img src="<?= base_url('assets/img/productos/' . $imagen_old); ?>" alt="Imagen producto actuall" style="max-width: 150px; height: auto;">
                        </div>
                    <?php endif; ?>
                    <?php if ($validation->hasError('imagen')): ?>
                        <div class="alert alert-danger mt-2">
                            <?= $validation->getError('imagen'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!--BOTONES-->
                <div class="form-group">
                    <button type="submit" id="send_form" class="btn btn-success">Guardar Cambios</button>
                    <button type="reset" class="btn btn-danger">Restablecer</button>
                    <a href="<?= base_url('crear'); ?>" class="btn btn-secondary">Volver a Productos</a>
                </div>
            </div>
        </form> 
    </div>
</div>