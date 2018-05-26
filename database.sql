CREATE DATABASE IF NOT EXISTS almacen;
USE almacen;

CREATE TABLE IF NOT EXISTS grupos(
id            int(255) auto_increment not null,
nombre        varchar(255) not null,
descripcion   text,
CONSTRAINT pk_grupos PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categorias(
id            int(255) auto_increment not null,
nombre        varchar(255) not null,
descripcion   text,
CONSTRAINT pk_categorias PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS etiquetas(
id            int(255) auto_increment not null,
nombre        varchar(255) not null,
descripcion   text,
CONSTRAINT pk_etiquetas PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS usuarios(
id          int(255) auto_increment not null,
grupo_id    int(255) not null,
nombre      varchar(20) not null,
apellidos   varchar(255) not null,
email       varchar(255) not null,
pass        varchar(255) not null,
role        varchar(255) not null,
img         varchar(255),
CONSTRAINT pk_usuarios PRIMARY KEY (id),
CONSTRAINT fk_usuarios_grupos FOREIGN KEY (grupo_id) REFERENCES grupos(id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS productos(
id              int(255) auto_increment not null,
usuario_id      int(255) not null,
categoria_id    int(255) not null,
nombre          varchar(20) not null,
descripcion     text,
precio          float,
fecha_caducidad date,
ubicacion       text,
img             varchar(255),
CONSTRAINT pk_productos PRIMARY KEY (id),
CONSTRAINT fk_productos_usuarios FOREIGN KEY(usuario_id) REFERENCES usuarios(id),
CONSTRAINT fk_productos_categorias FOREIGN KEY(categoria_id) REFERENCES categorias(id)
)ENGINE=InnoDB;

CREATE TABLE  IF NOT EXISTS producto_etiqueta(
id            int(255) auto_increment not null,
producto_id   int(255) not null,
etiqueta_id   int(255) not null,
CONSTRAINT pk_producto_etiqueta PRIMARY KEY (id),
CONSTRAINT fk_producto_etiqueta_productos FOREIGN KEY (producto_id) REFERENCES productos(id),
CONSTRAINT fk_producto_etiqueta_etiquetas FOREIGN KEY (etiqueta_id) REFERENCES etiquetas(id)
)ENGINE=InnoDB;