<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Articulos</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<style type="text/css">
  body{
    background: ;
  }
  img{
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    --border-size: 1px;
    border: var(--border-size) solid black; 
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
    $id = isset($_POST['codigo']) ? $_POST['codigo'] : '';

    // 2. Consulta de articulo
    $sql = "SELECT * FROM articulo WHERE codigo LIKE '%$id%'";

    // 3. Se añade $cnx dentro de mysqli_error()
    $res = mysqli_query($cnx, $sql) or die(mysqli_error($cnx));

    // 4. Obtener la estructura de la tabla
    $campos = mysqli_query($cnx, "DESCRIBE articulo") or die(mysqli_error($cnx));
  ?>

  <script>
    function eliminar() {
      let res=confirm("¿Esta seguro que desea eliminar?")
      return res;
    }
  </script>

  <div class="container">
    <h1 class="text-center text-secondary font-weight-bold p-4">Registro de Articulos</h1>
  
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
                <label>Foto</label>
                <input type="file" class="form-control mb-2" name="imagen" required></input>

                <label>Nombre</label>
                <input type="text" class="form-control mb-2" placeholder="Nombre" name="nom" required></input>

                <label>Categoria</label>
                <select class="form-select mb-3" name="cat" required>
                  <option value="" disabled selected>Seleccione una categoria</option>
                  <option value="Animal">Animal</option>
                  <option value="Religioso">Religioso</option>
                  <option value="Flores">Flores</option>
                  <option value="Letras">Letras</option>
                  <option value="Deporte">Deporte</option>
                  <option value="Otros">Otros</option>
                </select>

                <label>Tipo</label>
                <select class="form-select mb-3" name="tip" required>
                  <option value="" disabled selected>Seleccione un tipo</option>
                  <option value="Dije">Dije</option>
                  <option value="Anillo">Anillo</option>
                  <option value="Arete">Arete</option>
                  <option value="Pulsera">Pulsera</option>
                </select>

                <label>Stock</label>
                <input type="number" class="form-control mb-2" name="sto" min="1" placeholder="1" required></input>

                <label>Precio</label>
                <input type="number" class="form-control mb-2" name="pre" step="0.01" min="0" placeholder="0.00" required></input>
                
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
      		  <th scope="col" class="text-center">Foto</th>
      		  <th scope="col" class="text-center">Nombre</th>
            <th scope="col" class="text-center">Categoria</th>
            <th scope="col" class="text-center">Tipo</th>
            <th scope="col" class="text-center">Stock</th>
            <th scope="col" class="text-center">Precio</th>
            <th scope="col" class="text-center">Visible</th>
  				  <th scope="col" class="text-center" colspan="2">Acciones</th>
          </tr>
  		  </thead>
  		  <tbody>
          <?php while($col=mysqli_fetch_row($res)) { ?>
          <tr>
      		  <td class="text-center"><?php echo $col[0] ?></td>
            <td class="text-center"><img src='<?php echo $col[1] ?>'></td>
            <td class="text-center"><?php echo $col[2] ?></td>
            <td class="text-center"><?php echo $col[3] ?></td>
            <td class="text-center"><?php echo $col[4] ?></td>
            <td class="text-center"><?php echo $col[5] ?></td>
            <td class="text-center"><?php echo $col[6] ?></td>
            <td class="text-center"><?php echo $col[7] ?></td>
            <td class="text-center">
              <!-- Editar -->
      		    <a data-bs-toggle="modal" 
                data-bs-target="#exampleModalEditar?codigo=<?php echo $col[0] ?>" 
                class="btn btn-primary btn-sm" 
                title="Editar producto">
                <i class="bi bi-pencil-square"></i>
              </a>
              <!-- Eliminar -->
      		    <a href="eliminar.php?codigo=<?php echo $col[0] ?>" 
                class="btn btn-danger btn-sm" 
                onclick="return eliminar()"
                title="Eliminar producto">
                <i class="bi bi-trash"></i>
              </a>
              <!-- Ocultar o mostrar -->
              <?php if ((int)$col[7] === 1) { ?>
                <a href="ocultar.php?codigo=<?php echo $col[0]; ?>"
                  class="btn btn-warning btn-sm"
                  title="Ocultar producto"
                  onclick="return confirm('¿Deseas ocultar este producto?');">
                  <i class="bi bi-eye-slash"></i>
                </a>
              <?php } else { ?>
                <a href="mostrar.php?codigo=<?php echo $col[0]; ?>"
                  class="btn btn-success btn-sm"
                  title="Mostrar producto"
                  onclick="return confirm('¿Deseas mostrar este producto?');">
                  <i class="bi bi-eye"></i>
                </a>
              <?php } ?>
            </td>
    		  </tr>
        
          <!-- Modal -->
		      <div class="modal fade" id="exampleModalEditar?codigo=<?php echo $col[0] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Registro</h1>
        		      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="editar.php?codigo=<?php echo $col[0] ?>" enctype="multipart/form-data" method="POST">
                    <label>Foto</label>
                    <input type="file" class="form-control mb-2" name="imagen" value='<?php echo $col[1] ?>'></input>

                    <label>Nombre</label>
                    <input type="text" class="form-control mb-2" placeholder="Nombre" name="nom" value='<?php echo $col[2] ?>' required></input>

                    <label>Categoria</label>
                    <select class="form-select mb-3" name="cat" required>
                      <option value="" disabled <?php echo empty($col[3]) ? 'selected' : ''; ?>>Seleccione una categoría</option>
                      <option value="Animal" <?php echo ($col[3] == 'Animal') ? 'selected' : ''; ?>>Animal</option>
                      <option value="Religioso" <?php echo ($col[3] == 'Religioso') ? 'selected' : ''; ?>>Religioso</option>
                      <option value="Flores" <?php echo ($col[3] == 'Flores') ? 'selected' : ''; ?>>Flores</option>
                      <option value="Letras" <?php echo ($col[3] == 'Letras') ? 'selected' : ''; ?>>Letras</option>
                      <option value="Deporte" <?php echo ($col[3] == 'Deporte') ? 'selected' : ''; ?>>Deporte</option>
                      <option value="Otros" <?php echo ($col[3] == 'Otros') ? 'selected' : ''; ?>>Otros</option>
                    </select>

                    <label>Tipo</label>
                    <select class="form-select mb-3" name="tip" required>
                      <option value="" disabled <?php echo empty($col[4]) ? 'selected' : ''; ?>>Seleccione un tipo</option>
                      <option value="Dije" <?php echo ($col[4] == 'Dije') ? 'selected' : ''; ?>>Dije</option>
                      <option value="Anillo" <?php echo ($col[4] == 'Anillo') ? 'selected' : ''; ?>>Anillo</option>
                      <option value="Arete" <?php echo ($col[4] == 'Arete') ? 'selected' : ''; ?>>Arete</option>
                      <option value="Pulsera" <?php echo ($col[4] == 'Pulsera') ? 'selected' : ''; ?>>Pulsera</option>
                    </select>

                    <label>Stock</label>
                    <input type="number" class="form-control mb-2" placeholder="Stock" name="sto" value='<?php echo $col[5] ?>' required></input>

                    <label>Precio</label>
                    <input type="number" step="0.01" class="form-control mb-2" placeholder="Precio" name="pre" value='<?php echo $col[6] ?>' required></input>

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