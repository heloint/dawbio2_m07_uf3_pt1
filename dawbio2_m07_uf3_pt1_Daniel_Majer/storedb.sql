CREATE USER 'storeusr'@'localhost' IDENTIFIED BY 'storepass';

CREATE DATABASE storedb
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE storedb;

GRANT SELECT, INSERT, UPDATE, DELETE ON storedb.* TO 'storeusr'@'localhost';

CREATE TABLE users (
    id INTEGER auto_increment,
    username VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(40) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    role VARCHAR(10) NOT NULL DEFAULT 'staff',
    UNIQUE (firstname, lastname),
    PRIMARY KEY (id)
) ENGINE InnoDb;

INSERT INTO users VALUES (0, "user01", "pass01", "fname01", "lname01", "admin");
INSERT INTO users VALUES (0, "user02", "pass02", "fname02", "lname02", "admin");
INSERT INTO users VALUES (0, "user03", "pass03", "fname03", "lname03", "admin");
INSERT INTO users VALUES (0, "user04", "pass04", "fname04", "lname04", "staff");
INSERT INTO users VALUES (0, "user05", "pass05", "fname05", "lname05", "staff");
INSERT INTO users VALUES (0, "user06", "pass06", "fname06", "lname06", "staff");

CREATE TABLE products (
    id INTEGER auto_increment,
    code VARCHAR(20) NOT NULL UNIQUE,
    description VARCHAR(100) NOT NULL,
    price DOUBLE DEFAULT 0.0,
    category_id INTEGER NOT NULL,
    PRIMARY KEY (id)
) ENGINE InnoDb;

CREATE TABLE categories (
    id INTEGER auto_increment,
    code VARCHAR(20) NOT NULL UNIQUE,
    description VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE InnoDb;

CREATE TABLE warehouses (
  id INTEGER auto_increment,
  code VARCHAR(20) NOT NULL UNIQUE,
  address VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE InnoDb;

CREATE TABLE warehousesproducts (
  warehouse_id INTEGER,
  product_id INTEGER,
  stock INTEGER,
  PRIMARY KEY(warehouse_id, product_id)
) ENGINE InnoDb;

ALTER TABLE products ADD FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE warehousesproducts ADD FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE warehousesproducts ADD FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON UPDATE CASCADE ON DELETE RESTRICT;

INSERT INTO categories VALUES 
  (1, "catcode01", "catdesc01"),
  (2, "catcode02", "catdesc02"),
  (3, "catcode03", "catdesc03"),
  (4, "catcode04", "catdesc04"),
  (5, "catcode05", "catdesc05");

INSERT INTO products VALUES 
  (1, "prodcode01", "proddesc01", 101.0, 1),
  (2, "prodcode02", "proddesc02", 102.0, 1),
  (3, "prodcode03", "proddesc03", 103.0, 2),
  (4, "prodcode04", "proddesc04", 104.0, 2),
  (5, "prodcode05", "proddesc05", 105.0, 3),
  (6, "prodcode06", "proddesc06", 106.0, 4); 

INSERT INTO warehouses VALUES 
  (1, "warhcode01", "address1"),
  (2, "warhcode02", "address2"),
  (3, "warhcode03", "address3"),
  (4, "warhcode04", "address4"),
  (5, "warhcode05", "address5");

INSERT INTO warehousesproducts VALUES 
  (1, 1, 11),
  (1, 3, 13),
  (1, 5, 15),
  (2, 2, 21),
  (2, 3, 23),
  (2, 6, 26);
  
