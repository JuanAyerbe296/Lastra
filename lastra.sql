-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-10-2026 a las 02:42:48
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lastra`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo`
--

CREATE TABLE `articulo` (
  `codigo` int(11) NOT NULL,
  `foto` text NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `categoria` varchar(20) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `stock` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articulo`
--

INSERT INTO `articulo` (`codigo`, `foto`, `nombre`, `categoria`, `tipo`, `stock`, `precio`, `visible`) VALUES
(1, 'img/leon.jpg', 'Molde de león', 'Animal', 'Anillo', 9, 200.00, 1),
(2, 'img/rolex.jpg', 'Molde Reloj Rolex', 'Otros', 'Anillo', 6, 300.00, 1),
(3, 'img/dad.jpg', 'Molde de anillo DAD', 'Letras', 'Anillo', 5, 250.00, 1),
(4, 'img/mom.jpg', 'Molde de anillo MOM', 'Letras', 'Anillo', 5, 100.00, 1),
(5, 'img/mary.jpg', 'Molde Mary Jane', 'Flores', 'Dije', 10, 250.00, 1),
(6, 'img/beisbol.jpg', 'Molde pelota de beisbol', 'Deporte', 'Dije', 20, 150.00, 1),
(7, 'img/sello.jpg', 'Molde sello plano', 'Otros', 'Anillo', 10, 100.00, 1),
(8, 'img/corona.jpg', 'Molde de corona', 'Otros', 'Dije', 10, 250.00, 1),
(9, 'img/nudoinfinito.jpg', 'Nudo Infinito', 'Otros', 'Pulsera', 3, 250.00, 1),
(10, 'img/vintagedorado.jpg', 'Vintage Dorado', 'Religioso', 'Anillo', 10, 250.00, 1),
(11, 'img/promesa.jpg', 'Promesa x2', 'Religioso', 'Anillo', 14, 400.00, 1),
(12, 'img/tungstenonegro.jpg', 'Tungsteno Negro', 'Religioso', 'Anillo', 9, 400.00, 1),
(13, 'img/onixnegro.jpg', 'Onix Negro', 'Otros', 'Anillo', 10, 350.00, 1),
(14, 'img/setjuego.jpg', 'Set Juego 9 Moldes', 'Otros', 'Pulsera', 5, 250.00, 1),
(15, 'img/corazonpuntoluz.jpg', 'Corazón Punto de Luz', 'Otros', 'Arete', 7, 50.00, 1),
(16, 'img/aretemagnetico.jpg', 'Arete Magnetico Hombre', 'Otros', 'Arete', 3, 50.00, 1),
(17, 'img/aretelunaoro.jpg', 'Luna Oro 18Kt', 'Flores', 'Arete', 10, 100.00, 1),
(18, 'img/aretesacerorealezapearl.jpg', 'Acero Realeza Pearl 18Kt', 'Otros', 'Arete', 5, 100.00, 1),
(19, 'img/aretescorazondiamante.jpg', 'Corazón Diamante', 'Otros', 'Arete', 10, 150.00, 1),
(20, 'img/brazaleteplateado.jpg', 'Inoxidable Brazalete Plateado', 'Otros', 'Pulsera', 5, 40.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `Id` varchar(20) NOT NULL,
  `Nombre` varchar(20) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Puesto` varchar(20) NOT NULL,
  `Rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`Id`, `Nombre`, `Usuario`, `Password`, `Puesto`, `Rol`) VALUES
('001', 'Juan Ayerbe', 'Adan', '2896', 'Gerente', 'Admin'),
('002', 'Miriam Ayerbe', 'Miriam', '0699', 'Logistica', 'Empleado'),
('003', 'Jim Morrison', 'JimMo', 'Club27', 'Vocalista', 'Empleado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_compra`
--

CREATE TABLE `ordenes_compra` (
  `id_orden` int(11) NOT NULL,
  `codigo_orden` varchar(20) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `fecha_emision` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `igv` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes_compra`
--

INSERT INTO `ordenes_compra` (`id_orden`, `codigo_orden`, `id_proveedor`, `fecha_emision`, `subtotal`, `igv`, `total`, `fecha_registro`) VALUES
(1, 'OC-8848', 1, '0000-00-00', 200.00, 36.00, 236.00, '2026-09-18 21:43:54'),
(2, 'OC-9042', 1, '0000-00-00', 60.00, 10.80, 70.80, '2026-09-28 15:37:43'),
(3, 'OC-4256', 1, '0000-00-00', 250.00, 45.00, 295.00, '2026-10-05 16:05:43'),
(4, 'OC-4277', 2, '0000-00-00', 200.00, 36.00, 236.00, '2026-10-08 07:41:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_items`
--

CREATE TABLE `orden_items` (
  `id_item` int(11) NOT NULL,
  `id_orden` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `total_linea` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `orden_items`
--

INSERT INTO `orden_items` (`id_item`, `id_orden`, `descripcion`, `cantidad`, `precio_unitario`, `total_linea`) VALUES
(1, 1, 'Molde de cera color rosado', 10, 20.00, 200.00),
(2, 2, 'Cera color blanco', 4, 15.00, 60.00),
(3, 3, 'Cera color negro', 5, 50.00, 250.00),
(4, 4, 'Cera color Rojo', 10, 20.00, 200.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `cliente_nombre` varchar(100) NOT NULL,
  `cliente_email` varchar(100) NOT NULL,
  `cliente_celular` varchar(9) NOT NULL,
  `cliente_direccion` varchar(50) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha_pedido` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `cliente_nombre`, `cliente_email`, `cliente_celular`, `cliente_direccion`, `id_producto`, `cantidad`, `total`, `fecha_pedido`) VALUES
(1, 'Juan Perez', 'juanperez@gmail.com', '958028320', 'Av. Los Heroes 255 (SJM)', 1, 1, 200.00, '2026-09-24 04:22:29'),
(3, 'Kelly Porta', 'kellyzhita@gmail.com', '950222450', 'Surco', 2, 1, 300.00, '2026-09-24 06:25:52'),
(6, 'Luis Angel Flores', 'luisangel@gmail.com', '920764512', 'San Gabriel (VMT)', 20, 3, 120.00, '2026-10-22 05:44:44'),
(7, 'Mayra Leonor', 'mayraab@gmail.com', '995320588', 'Barranco', 11, 1, 400.00, '2026-10-22 05:46:27'),
(8, 'Roger Waters', 'rogerwat@gmail.com', '920764325', 'Miraflores', 9, 2, 500.00, '2026-10-22 05:48:16'),
(9, 'Cesar Salvatierra', 'cesarsalva@gmail.com', '955320454', 'Tablada de Lurin (VMT)', 16, 2, 100.00, '2026-10-22 07:12:07'),
(10, 'Edgar Ayerbe', 'edgarcondori@gmail.com', '995320566', 'San Gabriel (VMT)', 12, 1, 400.00, '2026-10-22 07:13:09'),
(11, 'Yony Balderrama', 'yonybalderrama@gmail.com', '950123456', 'Óvalo Las Palomas (VES)', 20, 2, 80.00, '2026-10-23 07:52:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `ruc` varchar(11) NOT NULL,
  `telefono` varchar(13) NOT NULL,
  `direccion` text NOT NULL,
  `contacto_nombre` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `razon_social`, `ruc`, `telefono`, `direccion`, `contacto_nombre`, `estado`) VALUES
(1, 'Distribuidora Global S.A.C.', '20123456789', '920541533', 'Surco', 'Pedro Suarez', 'Activo'),
(2, 'Distribuidora Real S.A.C.', '20123456780', '958028320', 'Surco', 'Luis Calderón', 'Activo'),
(3, 'Distribuidora Modal S.A.C', '20151412360', '958028320', 'Av. Grau 255', 'Piero Gael', 'Inactivo'),
(4, 'Distribuidora Makro S.A.C', '20151412580', '2553344', 'San Juan de Miraflores', 'Luis Angel', 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `rol`) VALUES
(1, 'Jim Morrison', 'club27@gmail.com', 'TheDoors27', 'Admin'),
(2, 'Juan Ayerbe', 'juanayerbe@gmail.com', '281996', 'Admin'),
(3, 'Miriam Ayerbe', 'miriamayerbe@gmail.com', '6101999', 'Empleado'),
(4, 'Adrian Cuevas', 'adriancuevas@gmail.com', '45456', 'Empleado');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  ADD PRIMARY KEY (`id_orden`),
  ADD UNIQUE KEY `codigo_orden` (`codigo_orden`),
  ADD KEY `fk_ordenes_proveedores` (`id_proveedor`);

--
-- Indices de la tabla `orden_items`
--
ALTER TABLE `orden_items`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_orden` (`id_orden`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulo`
--
ALTER TABLE `articulo`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  MODIFY `id_orden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `orden_items`
--
ALTER TABLE `orden_items`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  ADD CONSTRAINT `fk_ordenes_proveedores` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `orden_items`
--
ALTER TABLE `orden_items`
  ADD CONSTRAINT `orden_items_ibfk_1` FOREIGN KEY (`id_orden`) REFERENCES `ordenes_compra` (`id_orden`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `articulo` (`codigo`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
