<?php
/** Producto $producto */
?>
<h1><?= $producto->getNombre();?></h1>

<dl>
    <dt>Precio</dt>
    <dd>$ <?= $producto->getPrecio();?></dd>
    <dt>Categoría (ID)</dt>
    <dd><?= $producto->getIdCategoria();?></dd>
    <dt>Marca (ID)</dt>
    <dd><?= $producto->getIdMarca();?></dd>
    <dt>Descripción</dt>
    <dd><?= $producto->getDescripcion();?></dd>
</dl>
