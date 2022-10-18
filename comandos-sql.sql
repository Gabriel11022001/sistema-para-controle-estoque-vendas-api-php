-- create database db_api_sistema_controle_estoque_vendas;

use db_api_sistema_controle_estoque_vendas;
 -- Criando a tabela de categorias de produtos.
create table tbl_categorias_produtos(
	categoria_produto_id int not null primary key auto_increment,
    categoria_produto_descricao text not null,
    categoria_produto_status bool not null
);
select * from tbl_categorias_produtos;