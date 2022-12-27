CREATE DATABASE IF NOT EXISTS `rabbit_books`;
CREATE DATABASE IF NOT EXISTS `rabbit_books_test`;
CREATE USER 'user'@'%' IDENTIFIED BY 'pass';
GRANT ALL ON `rabbit_books`.* TO 'user'@'%' ;
GRANT ALL ON `rabbit_books_test`.* TO 'user'@'%' ;
