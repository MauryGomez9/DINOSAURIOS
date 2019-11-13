CREATE SCHEMA `dinosaurios` ;
CREATE USER 'dinosaurios'@'127.0.0.1' IDENTIFIED WITH mysql_native_password BY '12345';
GRANT ALL ON dinosaurios.* TO 'dinosaurios'@'127.0.0.1';
