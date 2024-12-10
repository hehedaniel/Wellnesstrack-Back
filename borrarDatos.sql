-- Así deshabilito  las restricciones de las tablas
SET foreign_key_checks = 0;
DELETE FROM `usuario_realiza_ejercicio`;
DELETE FROM `enlace`;
DELETE FROM `consumo_dia`;
DELETE FROM `peso`;
DELETE FROM `recetas`;
DELETE FROM `alimento`;
DELETE FROM `ejercicio`;
DELETE FROM `usuario`;
-- Así vuelvo a habilitar las restricciones de las tablas
SET foreign_key_checks = 1;