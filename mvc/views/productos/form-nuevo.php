<?php
/** @var \DaVinci\Models\Categoria[] $categorias */
/** @var \DaVinci\Models\Marca[] $marcas */
use DaVinci\Session\Session;
$errores = Session::flash('errores') ?? [];
$oldData = Session::flash('old_data') ?? [];
?>
<h1>Crear nuevo producto</h1>

<p>Completá el formulario con los datos del nuevo producto, y dale a "Grabar".</p>

<form action="#" method="post">
    <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" class="form-control" value="<?= $oldData['nombre'] ?? '';?>" <?= isset($errores['nombre']) ? 'aria-describedby="nombre-error"' : '';?>>
         <?php
        if(isset($errores['nombre'])): ?>
        <div class="alert alert-danger" id="nombre-error"><?= $errores['nombre'][0];?></div>
        <?php
        endif; ?>
    </div>
    <div class="form-group">
        <label for="precio">Precio</label>
        <input type="text" id="precio" name="precio" class="form-control" value="<?= $oldData['precio'] ?? '';?>" <?= isset($errores['precio']) ? 'aria-describedby="precio-error"' : '';?>>
        <?php
        if(isset($errores['precio'])): ?>
            <div class="alert alert-danger" id="precio-error"><?= $errores['precio'][0];?></div>
        <?php
        endif; ?>
    </div>
    <div class="form-group">
        <label for="id_categoria">Categoría</label>
        <select id="id_categoria" name="id_categoria" class="form-control" <?= isset($errores['id_categoria']) ? 'aria-describedby="id_categoria-error"' : '';?>>
            <option value="">Seleccioná la categoría</option>
            <?php
            foreach($categorias as $categoria): ?>
                <option value="<?= $categoria->getIdCategoria();?>" <?= ($oldData['id_categoria'] ?? null) == $categoria->getIdCategoria() ? 'selected' : '';?>><?= $categoria->getNombre();?></option>
            <?php
            endforeach; ?>
        </select>
        <?php
        if(isset($errores['id_categoria'])): ?>
            <div class="alert alert-danger" id="id_categoria-error"><?= $errores['id_categoria'][0];?></div>
        <?php
        endif; ?>
    </div>
    <div class="form-group">
        <label for="id_marca">Marca</label>
        <select id="id_marca" name="id_marca" class="form-control" <?= isset($errores['id_marca']) ? 'aria-describedby="id_marca-error"' : '';?>>
            <option value="">Seleccioná la marca</option>
            <?php
            foreach($marcas as $marca): ?>
                <option value="<?= $marca->getIdMarca();?>" <?= ($oldData['id_marca'] ?? null) == $marca->getIdMarca() ? 'selected' : '';?>>
                    <?= $marca->getNombre();?>
                </option>
            <?php
            endforeach; ?>
        </select>
        <?php
        if(isset($errores['id_marca'])): ?>
            <div class="alert alert-danger" id="id_marca-error"><?= $errores['id_marca'][0];?></div>
        <?php
        endif; ?>
    </div>
    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" class="form-control" <?= isset($errores['descripcion']) ? 'aria-describedby="descripcion-error"' : '';?>><?= $oldData['descripcion'] ?? '';?></textarea>
         <?php
         if(isset($errores['descripcion'])): ?>
             <div class="alert alert-danger" id="descripcion-error"><?= $errores['descripcion'][0];?></div>
         <?php
         endif; ?>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Grabar</button>
</form>
