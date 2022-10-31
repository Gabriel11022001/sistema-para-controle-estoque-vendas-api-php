-- create database db_api_sistema_controle_estoque_vendas;

use db_api_sistema_controle_estoque_vendas;

 -- Criando a tabela de categorias de produtos.
create table tbl_categorias_produtos(
	categoria_produto_id int not null primary key auto_increment,
    categoria_produto_descricao text not null,
    categoria_produto_status bool not null
);

-- Criando a tabela de produtos.alter
create table tbl_produtos(
	produto_id int not null primary key auto_increment,
    produto_nome text not null,
    produto_descricao text not null,
    produto_codigo_barras text not null,
    produto_preco_venda decimal(10, 2) not null,
    produto_qtd_unidades_estoque int not null,
    produto_status bool not null,
    categoria_produto_id int not null,
    constraint foreign key(categoria_produto_id) references tbl_categorias_produtos(categoria_produto_id)
);

select * from tbl_categorias_produtos;

select * from tbl_produtos;