<?php
/** @var Producto[] $productos */
?>

<h1>Listado de Productos</h1>

<p>Acá podés ver todos los <b>PRODUCTOS</b> que tenemos en catálogo.</p>

<!-- Este link se supone que solo lo va a ver un usuario autenticado. -->
<!--<p>O podés también <a href="--><?//= \DaVinci\Core\App::getUrlPath() . 'productos/nuevo';?><!--">crear uno nuevo</a>.</p>-->
<p>O podés también <a href="<?= url('productos/nuevo');?>">crear uno nuevo</a>.</p>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Categoría</th>
        <th>Marca</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($productos as $producto): ?>
    <tr>
        <td><?= $producto->getIdProducto();?></td>
        <td><?= $producto->getNombre();?></td>
        <td><?= $producto->getPrecio();?></td>
        <td><?= $producto->getCategoria()->getNombre();?></td>
        <td><?= $producto->getMarca()->getNombre();?></td>
        <td>
            <a href="<?= url('productos/' . $producto->getIdProducto());?>">Ver más</a>
            <a href="<?= url('productos/' . $producto->getIdProducto()) . '/eliminar';?>">Eliminar</a>
        </td>
    </tr>
    <?php
    endforeach; ?>
    </tbody>
</table>
