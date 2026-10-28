<?php 
include 'conexion.php';
include 'header.php';
?>

<style>
.hero {
    height: 90vh;
    background-image: linear-gradient(to left, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0) 100%), url("img/joyero.jpg");
    background-size: cover;
    background-position: center;
    display: flex;
    justify-content: start;
    align-items:center;
    text-align: start;
    color: black;
}
.hero2 {
    height: 90vh;
    background-image: linear-gradient(to left, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0) 100%), url("img/joyero.jpg");
    background-size: cover;
    background-position: center;
    display: flex;
    justify-content: end;
    align-items:center;
    text-align: start;
    color: black;
}
.about-content-box {
    /* Fondo blanco semi-transparente */
    background-color: rgba(255, 255, 255, 0.95); 
    padding: 2.5rem;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); /* Sombra suave */
    margin-right: 5rem;
    /* Tamaño y Posición */
    max-width: 600px; /* Limita el ancho para que sea legible */
    color: var(--text-color); /* Asegura texto oscuro */
}
.about-content-box h2 {
    font-family: 'Noto Serif Display', serif; /* Usa tu fuente de títulos */
    font-size: 2.5em;
    color: var(--primary-color);
    margin-top: 0;
    margin-bottom: 1rem;
}
.about-content-box p {
    font-size: 1rem;
    line-height: 1.7; /* Texto más espaciado y legible */
    margin-bottom: 1.5rem;
}
.about-content-box .cta-button {
    display: inline-block; 
}
.hero-content {
    padding: 4rem 5%;
}
.hero p {
    font-family: 'Noto Serif Display', serif;
    font-size: 5em;
    font-weight: 700;
    color: #1a325a;
    line-height: 1.2;
    font-stretch: 62.5%;
    margin-block-end: 0.5em;
}
/*INDEX BUTTOM*/
.cta {
    border: none;
    background: none;
    cursor: pointer;
}
.cta span {
    padding-bottom: 7px;
    letter-spacing: 4px;
    font-size: 14px;
    padding-right: 15px;
    text-transform: uppercase;
}
.cta svg {
    transform: translateX(-8px);
    transition: all 0.3s ease;
}
.cta:hover svg {
    transform: translateX(0);
}
.cta:active svg {
    transform: scale(0.9);
}
.hover-underline-animation {
    position: relative;
    color: black;
    padding-bottom: 20px;
}
.hover-underline-animation:after {
    content: "";
    position: absolute;
    width: 100%;
    transform: scaleX(0);
    height: 2px;
    bottom: 0;
    left: 0;
    background-color: #000000;
    transform-origin: bottom right;
    transition: transform 0.25s ease-out;
}
.cta:hover .hover-underline-animation:after {
    transform: scaleX(1);
    transform-origin: bottom left;
}
.cta-button {
    text-decoration: none;
    background-color: none;
    color: rgb(0, 0, 0);
    padding: 0.75rem 1.5rem;
    border-color: black;
    border: 1px solid #000000;
    border-radius: 30px;
    font-weight: bold;
    transition: background-color 0.5s ease;
    transition: color 0.3s ease;
    align-items: center;
}
.cta-button:hover {
    background-color: #253b56; /* Un tono más oscuro */
    color : rgb(255, 255, 255);
}
.homeicon {
    width: 30px;
    height: 30px;
}
.breadcrumb {
    align-items: center;
    align-content: center;
    /* Quita los estilos de lista predeterminados */
    list-style: none;
    padding: 0;
    margin: 0px 177px;
    flex-direction:row;
    /* Para que los elementos se muestren en línea */
    display: flex;
    font-size: 14px; /* Ajusta el tamaño de fuente */
    color: #6c757d; /* Color para el texto inactivo */
}
.breadcrumb-item a {
    /* Estilo para los enlaces activos */
    text-decoration: none;
    color: #000000; /* Color para los enlaces */
    white-space: nowrap; /* Evita que los enlaces se rompan */
}
.breadcrumb-item + .breadcrumb-item::before {
    /* Esto crea el separador '>', aplicado a todos excepto el primero */
    content: ">";
    padding-right: 8px;
    padding-left: 8px;
    color: #6c757d; /* Color del separador */
    display: inline-block;
}
.breadcrumb-item.active {
    /* Estilo para el elemento de la página actual */
    color: #343a40; /* Un color más oscuro para el elemento activo */
    font-weight: 500; /* Opcional: para que se destaque un poco */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis; /* Opcional: para manejar títulos muy largos */
    max-width: 400px; /* Limita el ancho del título, ajusta según necesidad */
}
</style>

<main>       
    <section class="hero2">
        <div class="about-content-box">
            <h2>Nosotras</h2>
            <p>Desde hace más de 20 años, en Platería Lastra transformamos metales preciosos y piedras de calidad en piezas exclusivas que marcan la diferencia. Nuestra pasión por el detalle y la perfección convierte cada joya en un símbolo de elegancia para quienes la llevan.</p>
            <p>Nuestra misión es combinar tradición e innovación, ofreciendo productos que superen las expectativas de nuestros clientes y fomenten la creatividad en el arte de la joyería.</p>    
            <a href="contacto.php" class="cta-button">Contáctanos</a>
        </div>
    </section>
</main>
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