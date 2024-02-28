
create database db_VintageFilmVault;

-- drop database db_VintageFilmVault;

use db_vintagefilmvault;

CREATE TABLE tb_cliente (
    id_cliente INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    cd_cliente INT UNIQUE NOT NULL,
    nm_cliente VARCHAR(50) NOT NULL,
    nm_email VARCHAR(50) UNIQUE NOT NULL,
    cd_senha VARCHAR(15) NOT NULL,
    nm_endereco VARCHAR(100) NOT NULL,
    status_cadastro ENUM('Aprovado', 'Recusado', 'Pendente'),
    status_film ENUM('Alugou', 'Não Alugou')
);

CREATE TABLE tb_administrador (
    id_adm INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    cd_adm INT UNIQUE NOT NULL,
    nm_administrador VARCHAR(50) NOT NULL,
    nm_login VARCHAR(10) UNIQUE NOT NULL,
    cd_senha VARCHAR(15) NOT NULL
);

insert tb_administrador(cd_adm, nm_administrador, nm_login, cd_senha) values(49355, "Pedro Henrique Silva", "ADM1", "adm123");

CREATE TABLE tb_filmes (
    id_filme INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nm_filme VARCHAR(50) NOT NULL,
    ano_lancamento INT NOT NULL,
    vl_filme FLOAT(10 , 2 ) NOT NULL,
    tipo_midia ENUM('Digital', 'Física', 'Ambos') NOT NULL,
    dsc_filme varchar(400),
    filme_poster VARCHAR(255) NOT NULL
);

CREATE TABLE tb_generos (
    id_genero INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nm_genero VARCHAR(50)
);

CREATE TABLE tb_InfoFilme (
    id_InfoFilme INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fk_filme INT,
    fk_genero INT,
    FOREIGN KEY (fk_filme)
        REFERENCES tb_filmes (id_filme),
    FOREIGN KEY (fk_genero)
        REFERENCES tb_generos (id_genero)
);

CREATE TABLE tb_filme_alugado (
    id_filme_alugado INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    tipo_midia enum("Digital", "Física", "Ambos"),
    fk_filme INT,
    fk_cliente INT,
    FOREIGN KEY (fk_filme)
        REFERENCES tb_filmes (id_filme),
    FOREIGN KEY (fk_cliente)
        REFERENCES tb_cliente (id_cliente)
);


-- Inserts de Exemplo



INSERT INTO tb_filmes (nm_filme, ano_lancamento, vl_filme, tipo_midia, dsc_filme, filme_poster) VALUES
('Filme 1', 2020, 19.99, 'Digital', 'Descrição do Filme 1', 'poster1.jpg'),
('Filme 2', 2015, 24.99, 'Física', 'Descrição do Filme 2', 'poster2.jpg'),
('Filme 3', 2018, 29.99, 'Ambos', 'Descrição do Filme 3', 'poster3.jpg'),
('Filme 4', 2022, 14.99, 'Digital', 'Descrição do Filme 4', 'poster4.jpg'),
('Filme 5', 2017, 21.99, 'Física', 'Descrição do Filme 5', 'poster5.jpg'),
('Filme 6', 2019, 17.99, 'Ambos', 'Descrição do Filme 6', 'poster6.jpg'),
('Filme 7', 2016, 22.99, 'Digital', 'Descrição do Filme 7', 'poster7.jpg'),
('Filme 8', 2021, 26.99, 'Física', 'Descrição do Filme 8', 'poster8.jpg'),
('Filme 9', 2014, 31.99, 'Ambos', 'Descrição do Filme 9', 'poster9.jpg'),
('Filme 10', 2023, 18.99, 'Digital', 'Descrição do Filme 10', 'poster10.jpg');

INSERT INTO tb_generos (nm_genero) VALUES
('Ação'),
('Comédia'),
('Drama'),
('Ficção Científica'),
('Romance'),
('Suspense'),
('Terror'),
('Animação'),
('Documentário'),
('Fantasia');


INSERT INTO tb_InfoFilme (fk_filme, fk_genero) VALUES
(1, 1),   -- Filme 1 (Ação)
(2, 2),   -- Filme 2 (Comédia)
(3, 3),   -- Filme 3 (Drama)
(4, 4),   -- Filme 4 (Ficção Científica)
(5, 5),   -- Filme 5 (Romance)
(6, 1),   -- Filme 6 (Ação)
(7, 2),   -- Filme 7 (Comédia)
(8, 3),   -- Filme 8 (Drama)
(9, 4),   -- Filme 9 (Ficção Científica)
(10, 5);  -- Filme 10 (Romance)

insert into tb_InfoFilme(fk_filme, fk_genero) values (6, 2);


-- StoreProcedures para a parte administrativa

DELIMITER //
CREATE PROCEDURE sp_consultar_adm(in login varchar(10), in senha varchar(15))
BEGIN
	SELECT cd_adm, nm_administrador FROM tb_administrador WHERE nm_login = login AND cd_senha = senha;
END ;
//
DELIMITER ; 

DELIMITER //
CREATE PROCEDURE sp_validar_adm(in codigoUnico int)
BEGIN
	SELECT cd_adm, nm_administrador FROM tb_administrador WHERE cd_adm = codigoUnico;
END ;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_cadastrar_filme(in nome varchar(50), in ano_lancamento int,  in vl_filme float(10,2), tipo_midia ENUM('Digital', 'Física', 'Ambos'), in dsc_filme varchar(400), in filme_poster varchar(255))
BEGIN
	INSERT INTO tb_filmes(nm_filme, ano_lancamento, vl_filme, tipo_midia, dsc_filme, filme_poster) values(nome, ano_lancamento, vl_filme, tipo_midia, dsc_filme, filme_poster);
END;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_delete_filme(in idfilme int)
BEGIN
	DELETE FROM tb_filmes WHERE id_filme = idfilme;
END ;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_cadastrar_genero_no_filme(in id_filme int, in id_genero int)
BEGIN
	insert tb_InfoFilme(fk_filme, fk_genero) values(id_filme, id_genero);
end;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_consultar_filme_por_id(in filmeID int)
begin 
	SELECT id_filme, nm_filme, vl_filme, tipo_midia FROM tb_filmes where id_filme = filmeID;
end ;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_consultar_clientes()
BEGIN
	SELECT id_cliente, nm_cliente, nm_email, nm_endereco, status_film FROM tb_cliente WHERE status_cadastro = "Aprovado";
END;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_consultar_clientes_pendentes()
BEGIN
	SELECT id_cliente, nm_cliente, nm_email, nm_endereco  FROM tb_cliente WHERE status_cadastro = "Pendente";
END;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_consultar_filmes()
BEGIN
	SELECT id_filme, nm_filme, vl_filme, tipo_midia FROM tb_filmes;
END;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_consultar_filmes_alugados()
BEGIN
	-- C tb_cliente F tb_filmes AL tb_filme_alugado
	SELECT AL.id_filme_alugado, C.id_cliente, C.nm_cliente, C.nm_email, F.nm_filme from tb_cliente C join tb_filme_alugado AL on AL.fk_cliente = C.id_cliente join tb_filmes F on AL.fk_filme = F.id_filme;
END ;
//
DELIMITER ;

-- call sp_consultar_filmes_alugados();

DELIMITER //
CREATE PROCEDURE sp_resgatar_generos()
BEGIN
	SELECT * FROM tb_generos;
END;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_consultar_cliente_pelo_nome(in nome varchar(50))
BEGIN
	SELECT 
    id_cliente, nm_cliente, nm_email
FROM
    tb_cliente
WHERE
    nm_cliente like CONCAT('%', nome, '%')
AND
	status_cadastro = "Aprovado";
END;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_consultar_filme_pelo_nome(in nome varchar(50))
BEGIN
	SELECT 
    id_filme, nm_filme, vl_filme, tipo_midia
FROM
    tb_filmes
WHERE
    nm_filme like CONCAT('%', nome, '%');
END;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_mudar_estado_do_cliente(in cliente int, in estado enum("Aprovado", "Recusado"))
begin
	update tb_cliente set status_cadastro = estado where id_cliente = cliente;
end ;
//
DELIMITER ; 

DELIMITER //
CREATE PROCEDURE sp_alugar_filme(in cliente int, in filme int, in tipo_midia enum("Digital", "Física", "Ambos"))
begin
	insert into tb_filme_alugado(tipo_midia, fk_filme, fk_cliente) values (tipo_midia, filme, cliente);
end ;
//
DELIMITER ;

drop procedure sp_alugar_filme;



DELIMITER //
CREATE PROCEDURE sp_devolver_filme(in filme_alugado int)
begin
	delete from tb_filme_alugado where id_filme_alugado = filme_alugado;
end ;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_verificar_se_usuario_alugou_filme(in id_cli int)
begin
	 select id_filme_alugado from tb_filme_alugado where fk_cliente = id_cli;
end ;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_zerar_filmes_alugados(in id_cli int)
begin
	update tb_cliente set status_film = "Não Alugou" where id_cliente = id_cli;
end ;
//
DELIMITER ;

-- StoreProcedures para usar na parte do cliente

DELIMITER //
CREATE PROCEDURE sp_selecionar_filmes_por_genero(in genero varchar(50))
-- F = tb_filmes,  G = tb_generos, IF = tb_InfoFilme
BEGIN
	Select F.id_filme, F.nm_filme, F.ano_lancamento, F.vl_filme, G.nm_genero from tb_filmes F join tb_InfoFilme InF on InF.fk_filme = F.id_filme join tb_generos G on InF.fk_genero = G.id_genero where G.nm_genero = genero;
END ;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_cadastrar_cliente(in codigo int, in nome varchar(50), in email varchar(50),in senha varchar(15),in endereco varchar(100))
BEGIN
	INSERT INTO tb_cliente(cd_cliente, nm_cliente, nm_email, cd_senha, nm_endereco) values(codigo, nome, email, senha, endereco);
END;
//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_consultar_filme_alugado_por_cliente(in id_cli int)
BEGIN
	SELECT AL.id_filmealugado, F.nm_nome, F.vl_filme, F.tipo_midia from tb_filmes F join tb_filme_alugado AL on AL.fk_filme = F.id_filme where AL.fk_cliente = id_cliente;
END; 
//
DELIMITER ;

-- Triggers

DELIMITER //
CREATE TRIGGER before_insert_tb_cliente
BEFORE INSERT ON tb_cliente
FOR EACH ROW
BEGIN
    SET NEW.status_cadastro = 'Pendente';
    SET NEW.status_film = 'Não Alugou';
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER after_insert_tb_filme_alugado
AFTER INSERT ON tb_filme_alugado
FOR EACH ROW
BEGIN
	update tb_cliente set status_film = "Alugou" where id_cliente = new.fk_cliente;
END;
//
DELIMITER ;