DROP TABLE IF EXISTS usuarios;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `contrasena` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_usuario` int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `telefono`, `contrasena`, `tipo_usuario`) VALUES
(1, 'Pepe', 'López', 'pepe@miclase.com', '666666666', 'holaquetal', 1),
(2, 'Rocío', 'Pérez', 'rocio@miclase.com', '6661423234', '12345678', 0),
(3, 'Arturo', 'Sánchez', 'arturo@miclase.com', '623463456', '12345', 0),
(4, 'Paquito', 'García', 'paquito@miclase.com', '623452345', 'paquito', 0),
(5, 'Alba', 'Ferrero', 'alba@miclase.com', '663456', '123456789', 0),
(6, 'Carlos', 'Jiménez', 'carlos@miclase.com', '66345636', 'amor', 0);