<?php 
include 'conexion.php';
include 'header.php';
?>

<style>
.contact-section {
    padding: 60px 20px;
    background-color: #f9f9f9; /* Fondo gris muy suave */
    min-height: 80vh; /* Para que ocupe buen espacio vertical */
    display: flex;
    align-items: center;
    justify-content: center;
}

.contact-container {
    max-width: 1100px;
    width: 100%;
    background: #fff;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05); /* Sombra elegante */
    border-radius: 12px;
    overflow: hidden;
    display: grid;
    grid-template-columns: 1fr 1.5fr; /* Columna info más pequeña que el form */
}

.contact-info-box {
    background-color: #122033; /* Azul oscuro de tu marca */
    color: #fff;
    padding: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.section-title {
    font-size: 2rem;
    margin-bottom: 15px;
    font-weight: 700;
}
.contact-desc {
    margin-bottom: 40px;
    opacity: 0.8;
    line-height: 1.6;
}
.info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 30px;
}
.info-item i {
    font-size: 1.5rem;
    color: #c4a77d; /* Dorado */
    margin-right: 20px;
    margin-top: 5px;
}
.info-item h4 {
    font-size: 1.1rem;
    margin-bottom: 5px;
    color: #fff;
}
.info-item p {
    font-size: 0.95rem;
    opacity: 0.8;
    margin: 0;
}
.social-links-contact {
    margin-top: auto; /* Empuja iconos al final */
    display: flex;
    gap: 15px;
}
.social-links-contact a {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.1);
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    border-radius: 50%;
    text-decoration: none;
    transition: 0.3s;
}
.social-links-contact a:hover {
    background: #c4a77d;
    color: #122033;
}

.contact-form-box {
    padding: 50px;
}
.input-group {
    margin-bottom: 20px;
}
.input-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
}
.input-group input,
.input-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    font-family: inherit;
    transition: 0.3s;
    background-color: #fbfbfb;
}
.input-group input:focus,
.input-group textarea:focus {
    border-color: #122033; /* Azul al enfocar */
    background-color: #fff;
    outline: none;
}
.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background-color: #122033;
    color: #fff;
    padding: 12px 30px;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    cursor: pointer;
    transition: 0.3s;
    font-weight: 600;
}
.btn-submit:hover {
    background-color: #c4a77d; /* Dorado al hover */
    color: #122033;
    transform: translateY(-2px);
}
.dropdown-mobile {
    display: none;
}	
</style>

<section class="contact-section">
    <div class="contact-container">
        
        <div class="contact-info-box">
            <h2 class="section-title">Contactanos</h2>
            <p class="contact-desc">
                ¿Tienes alguna duda sobre una cera o deseas un diseño personalizado? 
                Estamos aquí para ayudarte.
            </p>
            
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h4>Ubicación</h4>
                    <p>Ica No 242 Int. 311, Lima, Lima </p>
                </div>
            </div>

            <div class="info-item">
                <i class="fas fa-phone-alt"></i>
                <div>
                    <h4>Teléfono</h4>
                    <p>+51 923 979 139</p>
                </div>
            </div>

            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <h4>Email</h4>
                    <p>contacto@platerialastra.com</p>
                </div>
            </div>

            <div class="social-links-contact">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>

        <div class="contact-form-box">
            <form id="form-contacto">
                <div class="input-group">
                    <label for="user_name">Nombre completo</label>
                    <input type="text" name="user_name" id="user_name" required placeholder="Ej: Juan Pérez">
                </div>

                <div class="input-group">
                    <label for="user_email">Correo electrónico</label>
                    <input type="email" name="user_email" id="user_email" required placeholder="ejemplo@correo.com">
                </div>

                <div class="input-group">
                    <label for="subject">Asunto</label>
                    <input type="text" name="subject" id="subject" required placeholder="Consulta sobre anillos">
                </div>

                <div class="input-group">
                    <label for="message">Mensaje</label>
                    <textarea name="message" id="message" rows="5" required placeholder="Escribe tu mensaje aquí..."></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    Enviar Mensaje <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>

<script type="text/javascript">
   // INICIALIZACIÓN DE EMAILJS
   // IMPORTANTE: Debes registrarte gratis en emailjs.com para obtener tu "Public Key"
   (function(){
      emailjs.init("TU_PUBLIC_KEY_AQUI"); 
   })();
</script>

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