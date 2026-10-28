<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css" integrity="sha512-QeR2VH+lsBE5LSAe1Q5EnTBbe7XTBubt8dG93Y7gidSgdMCr8nVqKcfKAMyN96SV8KDbZVTDXChatu5G2KQGzg==" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="header">
      <div class="logo">
        <img src="img/logo.png" alt="">
      </div>
      <nav>
        <ul class="nav-links">
          <li><a href="emp/empleado.php">Empleado</a></li>
          <li><a href="pro/proveedor.php">Proveedor</a></li>
          <li><a href="art/articulo.php">Articulo</a></li>
          <li><a href="ord/index.php">Orden de Compra</a></li>
          <li><a href="pedido.php">Pedido</a></li>
          <li><a href="dashboard.php">Dashboard</a></li>
        </ul> 
      </nav>

      <div class="actions">
        <a href="inicio.php" class="btn">Ir a Catálogo</a>
        <a href="logout.php" class="btn">Log out</a>
      </div>

      <!--<a href="inicio.php" class="btn"><button>Ir a Catálogo</button></a>
      <a href="logout.php" class="btn"><button>Log out</button></a>--> 
    </header>

    <div class="contenedor">
      <div class="wrap">
        <div class="box">
          <!--<span>Amplia variedad de ceras para casting...</span>
          <h1>TODO NACE EN LA CERA</h1>
          <p>No se trata solo de joyas, sino de explorar, intervenir y expandir. Hoy es momento de crear sin limites.</p>
          <div class="botones">
            <a href="#" class="btn1">Suscríbete</a>
            <a href="catalogo.php" class="btn2">Catálogo</a>          
          </div>-->
        </div>
      </div>
    </div>

    <div class="container">
        <div class="columna">
          <h3>SOBRE PLATERÍA LASTRA</h3> <br>
          <p>Artesanía y tradición en cada pieza.</p>
          <p>Creamos joyería y orfebrería de</p>
          <p>plata con diseños exclusivos y la</p>
          <p>máxima calidad desde hace</p>
          <p>generaciones.</p>
        </div>

        <div class="columna">
          <h3>ENLACES RÁPIDOS</h3> <br>
          <ul class="nav-links">
            <li><a href="#">Nosotros</a></li> <br>
            <li><a href="#">Catálogo</a></li> <br>
            <li><a href="#">Cuidado de la Plata</a></li> <br>
            <li><a href="#">Contacto</a></li>
          </ul>
        </div>

        <div class="columna">
          <h3>SÍGUENOS</h3> <br>
          <div class="social-icons">
            <a href="#" class="social-link" aria-label="Instagram">
              <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="#" class="social-link" aria-label="Facebook">
              <i class="fa-brands fa-facebook-f"></i>
            </a>
            <a href="#" class="social-link" aria-label="Pinterest">
              <i class="fa-brands fa-pinterest-p"></i>
            </a>
          </div> <br>
          <div class="social-icons">
            <p><i class="fa-solid fa-envelope"></i> info@platerialastra.com</p>
          </div>
        </div>

        <div class="columna">
          <h3>VISÍTANOS</h3> <br>
          <div class="maps">
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1950.9792617861249!2d-77.0329620136131!3d-12.04637479570808!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c8b5fdf16bb3%3A0x6e9cef40d0255182!2sJir%C3%B3n%20Ica%20198%2C%20Lima%2015001!5e0!3m2!1ses!2spe!4v1764587487363!5m2!1ses!2spe"
              height="150" 
              style="border:0;" 
              allowfullscreen="" 
              loading="lazy" 
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
        </div>  
    </div>
    <hr>
    <div class="final">
      <div class="end"> 
        <p>© 2026 Platería Lastra. Todos los derechos reservados.</p>
      </div> 
    </div>
</body>
</html>