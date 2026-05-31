<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <div>
            <a href="<?php echo base_url('/usuarios');?>" class="btn btn-success">Volver</a>
        </div>
        <input type="text" class="form-control w-25" placeholder="Search..." id="searchInput">
    </div>
    <table class="table table-bordered table-striped text-center">
        <thead class="thead-light">
            <tr>
                <th>Id</th>
                <th>nombre</th>
                <th>apellido</th>
                <th>usuario</th>
                <th>email</th>
                <th>rol</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario->id_usuario  ?></td>
                    <td><?= $usuario->nombre ?></td>
                    <td><?= $usuario->apellido ?></td>
                    <td><?= $usuario->usuario?></td>
                    <td><?= $usuario->email ?></td>
                    <td>
                        <?= $usuario->perfil_id == 1 ? 'Admin' : ($usuario->perfil_id == 2 ? 'Cliente' : 'Otro') ?>
                    </td>
                    <td>
                        <a href="<?= site_url('activarUsu/' . $usuario->id_usuario ) ?>" class="btn btn-secondary btn-sm">Quitar Ban</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>