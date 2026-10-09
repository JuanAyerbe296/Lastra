<?php 
include 'conexion.php';
include 'header.php';

$codigo = isset($_GET['codigo']) ? intval($_GET['codigo']) : 0;

if ($codigo <= 0) {
    header('Location: catalogo.php');
    exit;
}

// Consulta segura con Prepared Statements
$stmt = mysqli_prepare($cnx, "SELECT * FROM articulo WHERE codigo = ?");
mysqli_stmt_bind_param($stmt, "i", $codigo);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$articulo = mysqli_fetch_assoc($resultado);

if (!$articulo) {
    echo "<div style='padding: 40px; text-align: center;'><h2>Producto no encontrado.</h2><a href='catalogo.php'>Volver al catálogo</a></div>";
    exit;
}
?>

<div class="container-detalle">
    <div class="breadcrumb">
        <a href="catalogo.php"><i class="fa fa-home"></i> &gt; CATÁLOGO</a>
    </div>

    <div class="detalle-grid">
        <!-- Imagen del Producto -->
        <div class="detalle-imagen">
            <img src="art/<?= htmlspecialchars($articulo['foto']) ?>" alt="<?= htmlspecialchars($articulo['nombre']) ?>">
        </div>

        <!-- Información del Producto -->
        <div class="detalle-info">
            <h1><?= htmlspecialchars($articulo['nombre']) ?></h1>
            <p class="descripcion">Cera en forma de <?= strtolower(htmlspecialchars($articulo['nombre'])) ?> estándar...</p>

            <div class="especificaciones">
                <div class="box-spec">
                    <span class="label">Categoría</span>
                    <strong><?= htmlspecialchars($articulo['categoria']) ?></strong>
                </div>
                <div class="box-spec">
                    <span class="label">Material / Tipo</span>
                    <strong><?= htmlspecialchars($articulo['tipo']) ?></strong>
                </div>
                <div class="box-spec">
                    <span class="label">Stock Disponible</span>
                    <strong><?= htmlspecialchars($articulo['stock']) ?> unidades</strong>
                </div>
            </div>

            <div class="precio-detalle">
                Precio: S/ <?= number_format($articulo['precio'], 2) ?>
            </div>

            <!-- Botón para abrir el Modal -->
            <?php if ($articulo['stock'] > 0): ?>
                <button type="button" class="btn-whatsapp" onclick="abrirModal(<?= $articulo['codigo'] ?>, '<?= addslashes(htmlspecialchars($articulo['nombre'])) ?>', <?= $articulo['precio'] ?>, <?= $articulo['stock'] ?>)">
                    Comprar
                </button>
            <?php else: ?>
                <button type="button" class="btn-whatsapp" disabled style="background-color: #ccc; cursor: not-allowed; border-color: #ccc;">
                    Agotado
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Formulario de Compra -->
<div id="modalCompra" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <h3 id="modalTitulo">Finalizar Compra</h3>
        
        <form action="procesar_compra.php" method="POST">
            <input type="hidden" id="id_producto" name="id_producto"><br>
            
            <div class="form-group">
                <label for="cliente_nombre">Nombre Completo:</label>
                <input type="text" id="cliente_nombre" name="cliente_nombre" required placeholder="Ej. Juan Pérez">
            </div>

            <div class="form-group">
                <label for="cliente_email">Correo Electrónico:</label>
                <input type="email" id="cliente_email" name="cliente_email" required placeholder="correo@ejemplo.com">
            </div>

            <div class="form-group">
                <label for="cliente_celular">N° Celular:</label>
                <input type="text" id="cliente_celular" name="cliente_celular" required placeholder="958..." maxlength="9" pattern="[0-9]{9}" inputmode="numeric">
            </div>

            <div class="form-group">
                <label for="cliente_direccion">Dirección:</label>
                <input type="text" id="cliente_direccion" name="cliente_direccion" required placeholder="av. los heroes (sjm)">
            </div>

            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" min="1" value="1" required oninput="calcularTotal()">
            </div>

            <div class="form-group">
                <p><strong>Total a Pagar:</strong> S/ <span id="totalPagar">0.00</span></p>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-comprar" style="width: 100%;">Confirmar Pedido</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Estilos opcionales si no están en tu CSS global */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 25px;
    border-radius: 8px;
    width: 90%;
    max-width: 450px;
    position: relative;
}

.close {
    position: absolute;
    right: 15px;
    top: 10px;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}

.btn-comprar {
    background-color: #1b3039;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}
</style>

<script>
    let precioUnitario = 0;

    function abrirModal(id, nombre, precio, stockMax) {
        document.getElementById('id_producto').value = id;
        document.getElementById('modalTitulo').innerText = "Comprar: " + nombre;
        
        let inputCantidad = document.getElementById('cantidad');
        inputCantidad.max = stockMax;
        inputCantidad.value = 1;
        
        precioUnitario = precio;
        calcularTotal();
        document.getElementById('modalCompra').style.display = 'block';
    }

    function cerrarModal() {
        document.getElementById('modalCompra').style.display = 'none';
    }

    function calcularTotal() {
        let cantInput = document.getElementById('cantidad');
        let cant = parseInt(cantInput.value) || 0;
        let max = parseInt(cantInput.max) || 1;

        if (cant > max) {
            cant = max;
            cantInput.value = max;
        }

        let total = cant * precioUnitario;
        document.getElementById('totalPagar').innerText = total.toFixed(2);
    }

    // Cerrar modal si el usuario hace clic fuera de la caja blanca
    window.onclick = function(event) {
        let modal = document.getElementById('modalCompra');
        if (event.target == modal) {
            cerrarModal();
        }
    }
</script>

</body>
</html>