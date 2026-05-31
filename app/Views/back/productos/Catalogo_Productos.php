<div class="container mt-5">
    <!--MENSAJES-->
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

    <div class="row mb-4">
        <div class="col-md-6">
        <!--formulario para filtrar-->
            <form action="<?= site_url('catalogo') ?>" method="get">
                <div class="input-group">
                    <label class="input-group-text" for="categoriaSelect">Filtrar por Categoría:</label>
                    <!--menú desplegable para seleccionar categoria-->
                    <select class="form-select" id="categoriaSelect" name="categoria_id" onchange="this.form.submit()"> <!--evento onchange envia autom-->
                        <option value="">Todas las categorías</option>
                        <?php foreach ($categorias as $categoria): ?> <!--iteramos sobre las categorias que tenemos-->
                            <option value="<?= $categoria->id ?>" <?= (isset($selectedCategory) && $selectedCategory == $categoria->id) ? 'selected' : '' ?>><!--sostenemos-->
                                <?= $categoria->descripcion ?><!--mostramos el titulo de cada categoria-->
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <!-- manejamos la no exitencia de productos en alguna categoria-->
        <?php if (empty($productosCat)): ?>
            <div class="col-12 text-center">
                <p>No hay productos disponibles en esta categoría.</p>
            </div>
        <?php else: ?>

            <!-- mostramos el catalogo de productos-->
            <?php foreach ($productosCat as $producto): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?= base_url('assets/uploads/' . $producto->imagen) ?>" class="card-img-top" alt="<?= esc($producto->nombre_prod) ?>">

                    <!-- manejamos ofertas y las ultimas unidades-->
                    <div class="position-absolute top-0 end-0 m-2">
                        <?php if($producto->stock <= $producto->stock_min): ?>
                            <span class="badge bg-danger">Últimas unidades</span>
                        <?php elseif($producto->precio_vta < $producto->precio): ?>
                            <span class="badge bg-warning text-dark">Oferta</span>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title"><?= esc($producto->nombre_prod) ?></h5>
                        <p class="card-text text-muted"><?= esc($producto->descripcion ?? 'Descripción no disponible') ?></p>

                        <div class="mb-2">
                            <span class="h5 text-primary">$<?= number_format($producto->precio_vta, 2) ?></span>
                            <?php if($producto->precio_vta < $producto->precio): ?>
                                <small class="text-decoration-line-through text-muted ms-2">$<?= number_format($producto->precio, 2) ?></small>
                            <?php endif; ?>
                        </div>

                        <!-- formu para agregar al carrito-->
                        <form method="post" action="<?= site_url('carrito/agregar') ?>">
                            <input type="hidden" name="product_id" value="<?= $producto->id ?>">
                            <div class="input-group mb-3" style="max-width: 150px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="this.nextElementSibling.stepDown()">-</button>
                                <input type="number" class="form-control text-center" name="quantity" value="1" min="1" max="<?= $producto->stock ?>">
                                <button class="btn btn-outline-secondary" type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                            </div>

                            <button class="btn btn-primary w-100" type="submit">
                                <i class="fas fa-cart-plus me-2"></i>Añadir al carrito
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
