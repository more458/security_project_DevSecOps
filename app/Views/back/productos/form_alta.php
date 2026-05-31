
    

<!--ACTUALIZACION 28/5/25-->
<div class="container mt-1 mb-1 d-flex justify-content-center">
    <div class="card" style="width:75%;">
        <div class="card-header text-center">
            <h2>Alta de Productos</h2>
        </div>
        <!-- Validación -->
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

        <!-- Inicio del formulario -->
        <form action="<?= base_url('enviar-prod'); ?>" method="post" enctype="multipart/form-data">
            <div class="card-body" media="(max-width:568px)">
                <div class="mb-2">
                    <label for="nombre_prod" class="form-label">Producto</label>
                    <input class="form-control" type="text" name="nombre_prod" id="nombre_prod" 
                    value="<?= set_value('nombre_prod'); 
                    ?>" placeholder="Nombre del producto" autofocus>
                    
                    <!-- Error -->
                    <?php if ($validation->getError('nombre_prod')): ?>
                        <div class="alert alert-danger mt-2">
                            <?= $validation->getError('nombre_prod'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                <label class="mt-2" for="age">Categoria</label>
                    <select class="form-control" name="categoria_id" id="categoria" value="">
                        <option value="">Seleccionar Categoria</option>
                        <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?php echo $categoria->id; ?>"> // Corregido: $categoria->id
                                <?php echo $categoria->id, ". ", $categoria->descripcion; // Corregido-
                            } ?>
                            </option>
                        </div>
                        <?php if($validation->getError('categoria')) {?>
                            <div class='alert alert-danger mt-2'>
                                <?php $error = $validation->getError('categoria'); ?>
                                <?= $error ?>
                            </div>
                        <?php } ?>
                    </select>
                
                <div class="mb-2">
                    <label for="precio" class="form-label">Precio de Costo</label>
                    <input class="form-control" type="text" name="precio" id="precio" value="<?= set_value('precio'); ?>">
                    <!-- Error -->
                    <?php if ($validation->getError('precio')): ?>
                        <div class="alert alert-danger mt-2">
                            <?= $validation->getError('precio'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-2">
                    <label for="precio_vta" class="form-label">Precio de Venta</label>
                    <input class="form-control" type="text" name="precio_vta" id="precio_vta" value="<?= set_value('precio_vta'); ?>">
                    <!-- Error -->
                    <?php if ($validation->getError('precio_vta')): ?>
                        <div class="alert alert-danger mt-2">
                            <?= $validation->getError('precio_vta'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-2">
                    <label for="stock" class="form-label">Stock</label>
                    <input class="form-control" type="text" name="stock" id="stock" value="<?= set_value('stock'); ?>">
                    <!-- Error -->
                    <?php if ($validation->getError('stock')): ?>
                        <div class="alert alert-danger mt-2">
                            <?= $validation->getError('stock'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-2">
                    <label for="stock_min" class="form-label">Stock Mínimo</label>
                    <input class="form-control" type="text" name="stock_min" id="stock_min" value="<?= set_value('stock_min'); ?>">
                    <!-- Error -->
                    <?php if ($validation->getError('stock_min')): ?>
                        <div class="alert alert-danger mt-2">
                            <?= $validation->getError('stock_min'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-2">
                    <label for="imagen" class="form-label">Imagen</label>
                    <input class="form-control" type="file" name="imagen" id="imagen" accept="image/png, image/jpg, image/jpeg">
                    <!-- Error -->
                    <?php if ($validation->getError('imagen')): ?>
                        <div class="alert alert-danger mt-2">
                            <?= $validation->getError('imagen'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Botones -->
                <div class="form-group">
                    <button type="submit" id="send_form" class="btn btn-success">Enviar</button>
                    <button type="reset" class="btn btn-danger">Cancelar</button>
                    <a href="<?= base_url('crear'); ?>" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </form> <!-- Fin del formulario -->
    </div>
</div>