CREATE DATABASE IF NOT EXISTS gerenciador;

USE gerenciador;

CREATE TABLE IF NOT EXISTS produto(
    id int auto_increment primary key not null,
    data_cadastro datetime,
    descricao char(50),
    estoque int default 0,
    imagem char(100),
    data_publicacao datetime
);

CREATE TABLE IF NOT EXISTS tipo_fatura(
    sigla char(2) primary key not null,
    descricao char(50)
);

INSERT INTO tipo_fatura (sigla, descricao) 
VALUES ('E', 'Entrada de produtos'); 


INSERT INTO tipo_fatura(sigla, descricao)
VALUES ('S', 'Saída de produtos');

CREATE TABLE IF NOT EXISTS fatura(
    id bigint auto_increment primary key,
    tipo_fatura char(2) not null,
    data_cadastro datetime,
    descricao varchar(255),
    FOREIGN KEY(tipo_fatura) REFERENCES tipo_fatura(sigla)
);

CREATE TABLE IF NOT EXISTS item_fatura(
    id bigint auto_increment primary key,
    id_fatura bigint not null,
    id_produto int not null,
    quantidade int ,
    preco decimal(10,2),
    FOREIGN KEY (id_fatura) REFERENCES fatura(id),
    FOREIGN KEY (id_produto) REFERENCES produto(id)
);

CREATE TABLE IF NOT EXISTS categoria(
    id int not null auto_increment primary key,
    descricao char(50),
    id_pai int not null
);


CREATE TABLE IF NOT EXISTS categoria_produto(
    id int not null auto_increment primary key,
    id_produto int not null,
    id_categoria int not null,
    pai_filha_sub int,
    FOREIGN KEY (id_produto) REFERENCES produto(id),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id)
);
