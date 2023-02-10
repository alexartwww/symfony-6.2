DROP DATABASE IF EXISTS symfony;
CREATE DATABASE IF NOT EXISTS symfony;
grant usage on *.* to 'symfony'@'localhost' IDENTIFIED BY 'Kae0eiquPanae3xoreiHoo0o';
grant all privileges on symfony.* to 'symfony'@'localhost';
grant usage on *.* to 'symfony'@'%' IDENTIFIED BY 'Kae0eiquPanae3xoreiHoo0o';
grant all privileges on symfony.* to 'symfony'@'%';
