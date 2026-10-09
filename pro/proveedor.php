<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.8">
  <title>Registro de Proveedores</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<style type="text/css">
  body{
    background: ;
  }
  img{
    border-radius: 50px;
    --border-size: 2px;
    border: var(--border-size) solid black;
    border-image-width: var(--border-size); 
  }
  .pag_btn {
    border-radius: 4px;
    margin: 4px;
    padding: 5px;
    cursor: pointer;
    border: none;
  }
  .pag_btn_des {
    border-radius: 4px;
    margin: 4px;
    padding: 5px;
    font-size: 14pt;
    cursor: pointer;
    border: none;
  }
  .pag_num {
    border-radius: 4px;
    margin: 4px;
    padding: 5px;  
    cursor: pointer;
    border: none;
  }
</style>
<body>

  <?php 
    include "conexion.php";

    // 1. Obtener el valor solo si viene en el POST; si no, dejarlo vacío
    $id = isset($_POST['id_proveedor']) ? $_POST['id_proveedor'] : '';

    // 2. Consulta de proveedores
    $sql = "SELECT * FROM proveedores WHERE id_proveedor LIKE '%$id%'";

    // 3. Se añade $cnx dentro de mysqli_error()
    $res = mysqli_query($cnx, $sql) or die(mysqli_error($cnx));

    // 4. Obtener la estructura de la tabla
    $campos = mysqli_query($cnx, "DESCRIBE proveedores") or die(mysqli_error($cnx));
  ?>

  <script>
    function eliminar() {
      let res=confirm("¿Esta seguro que desea eliminar?")
      return res;
    }
  </script>

  <div class="container">
    <h1 class="text-center text-secondary font-weight-bold p-4">Registro de Proveedores</h1>
  
    <div class="p-3 table-responsive">
		  <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal">Nuevo</button>
      <a href="../admin_panel.php" class="btn btn-dark mb-2">Regresar</a>
      <a href="reporte.php" class="btn btn-secondary mb-2">PDF</a>
      <a href="logout.php" class="btn btn-warning mb-2">Log Out</a>

      <nav class="navbar bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid justify-content-center">
          <form class="d-flex" role="search" method="POST">
            <input name="buscador" placeholder="Buscar con JS" id="codigo" class="form-control me-2 light-table-filter" type="search" aria-label="Search" data-table="table_id">
            <button name="buscador" class="btn btn-outline-light" type="submit">Buscar</button>
          </form>
        </div>
      </nav><br>

		  <!-- Modal -->
		  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  		  <div class="modal-dialog">
    		  <div class="modal-content">
      		  <div class="modal-header">
        		  <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo Registro</h1>
        		  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      		  </div>
      		  <div class="modal-body">
        		  <form action="guardar.php" enctype="multipart/form-data" method="POST">        
                <label>Razon Social</label>
                <input type="text" class="form-control mb-2" placeholder="Razon Social" name="raz" required></input>

                <label>RUC</label>
                <input type="text" class="form-control mb-2" placeholder="N° RUC" name="ruc" maxlength="11" pattern="[0-9]{11}" inputmode="numeric" required></input>

                <label>Telefono</label>
                <input type="text" class="form-control mb-2" placeholder="Telefono" name="tel" required></input>

                <label>Direccion</label>
                <input type="text" class="form-control mb-2" placeholder="Direccion" name="dir" required></input>

                <label>Nombre Contacto</label>
                <input type="text" class="form-control mb-2" placeholder="Contacto" name="con" required></input>

                <label>Estado</label>
                <select class="form-select mb-3" name="est" required>
                  <option value="" disabled selected>Seleccione un estado</option>
                  <option value="Activo">Activo</option>
                  <option value="Inactivo">Inactivo</option>
                </select>

                <input type="submit" value="Guardar" name="btnguardar" class="form-control btn btn-success">
        		  </form>
      		  </div>
    		  </div>
  		  </div>
		  </div>

		  <table class="table table-hover table-striped table-bordered border-secondary table_id" id="tblDatos">
  		  <thead class="table-dark text-white">
    		  <tr>
      		  <th scope="col" class="text-center">Codigo</th>
      		  <th scope="col" class="text-center">Razon Social</th>
      		  <th scope="col" class="text-center">RUC</th>
      		  <th scope="col" class="text-center">Telefono</th>
            <th scope="col" class="text-center">Direccion</th>
            <th scope="col" class="text-center">Nombre Contacto</th>
            <th scope="col" class="text-center">Estado</th>
  				  <th scope="col" class="text-center" colspan="2">Acciones</th>
          </tr>
  		  </thead>
  		  <tbody>
          <?php while($col=mysqli_fetch_row($res)) { ?>
          <tr>
      		  <td class="text-center"><?php echo $col[0] ?></td>
            <td class="text-center"><?php echo $col[1] ?></td>
            <td class="text-center"><?php echo $col[2] ?></td>
            <td class="text-center"><?php echo $col[3] ?></td>
            <td class="text-center"><?php echo $col[4] ?></td>
            <td class="text-center"><?php echo $col[5] ?></td>
            <td class="text-center"><?php echo $col[6] ?></td>
      		  <td>
      		    <center>
      		      <a data-bs-toggle="modal" data-bs-target="#exampleModalEditar?id_proveedor=<?php echo $col[0] ?>" class="btn btn-primary">Editar</a>
      		      <a href="eliminar.php?id_proveedor=<?php echo $col[0] ?>" class="btn btn-danger" onclick="return eliminar()">Eliminar</a>
      		    </center>
            </td>
    		  </tr>
        
          <!-- Modal -->
		      <div class="modal fade" id="exampleModalEditar?id_proveedor=<?php echo $col[0] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Registro</h1>
        		      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="editar.php?id_proveedor=<?php echo $col[0] ?>" enctype="multipart/form-data" method="POST">
                    <label>Razon Social</label>
                    <input type="text" class="form-control mb-2" placeholder="Razon Social" name="raz" value='<?php echo $col[1] ?>' required></input>

                    <label>RUC</label>
                    <input type="text" class="form-control mb-2" placeholder="N° RUC" name="ruc" value='<?php echo $col[2] ?>' required></input>

                    <label>Telefono</label>
                    <input type="text" class="form-control mb-2" placeholder="Telefono" name="tel" value='<?php echo $col[3] ?>' required></input>

                    <label>Direccion</label>
                    <input type="text" class="form-control mb-2" placeholder="Direccion" name="dir" value='<?php echo $col[4] ?>' required></input>

                    <label>Nombre Contacto</label>
                    <input type="text" class="form-control mb-2" placeholder="Contacto" name="con" value='<?php echo $col[5] ?>' required></input>

                    <label>Estado</label>
                    <select class="form-select mb-3" name="est" required>
                      <option value="" disabled <?php echo empty($col[6]) ? 'selected' : ''; ?>>Seleccione un estado</option>
                      <option value="Activo" <?php echo ($col[6] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                      <option value="Inactivo" <?php echo ($col[6] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                    </select>

                    <input type="submit" value="Editar" name="btneditar" class="form-control btn btn-success">
                  </form>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
        </tbody>
      </table>

      <div id="paginador" class="pagination justify-content-center"></div> 
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/page.js"></script>
  <script type="text/javascript" src="js/buscador.js"></script>
</body>
</html>