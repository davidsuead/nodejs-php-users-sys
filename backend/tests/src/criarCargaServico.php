<?php

require_once 'Conexao.php';
$dbh = Conexao::getInstance();

$sql = "INSERT INTO csg_prestador_servico (sigl_prestador_servico, nome_prestador_servico, info_prestador_servico, desc_url_amigavel, stat_prestador_servico) VALUES (:sigl_prestador_servico, :nome_prestador_servico, :info_prestador_servico, :desc_url_amigavel, :stat_prestador_servico)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":sigl_prestador_servico", 'OR1');
$stmt->bindValue(":nome_prestador_servico", 'Orgão Automatizado');
$stmt->bindValue(":info_prestador_servico", 'Orgão Automatizado');
$stmt->bindValue(":desc_url_amigavel", 'orgao_automatizado');
$stmt->bindValue(":stat_prestador_servico", 'A');
$stmt->execute();
$idPrestadorServico = $dbh->lastInsertId();

$sql = "INSERT INTO csg_usuario (numr_cpf_usuario) VALUES (:numr_cpf_usuario)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":numr_cpf_usuario", '00123451132');
$stmt->execute();
$idUsuario = $dbh->lastInsertId();


$sql = "INSERT INTO csg_usuario_prestador_servico (id_usuario, id_prestador_servico) VALUES (:id_usuario, :id_prestador_servico)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":id_usuario", $idUsuario);
$stmt->bindValue(":id_prestador_servico", $idPrestadorServico);
$stmt->execute();

$sql = "INSERT INTO csg_categoria_servico (nome_categoria_servico, indi_categoria_destaque, stat_categoria_servico, desc_url_amigavel, nome_classe_folha_estilo) VALUES (:nome_categoria_servico, :indi_categoria_destaque, :stat_categoria_servico, :desc_url_amigavel, :nome_classe_folha_estilo)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":nome_categoria_servico", 'Categoria Teste Automatizado');
$stmt->bindValue(":indi_categoria_destaque", 'S');
$stmt->bindValue(":stat_categoria_servico", 'A');
$stmt->bindValue(":desc_url_amigavel", 'categoria_teste_automatizado');
$stmt->bindValue(":nome_classe_folha_estilo", 'teste_auto');
$stmt->execute();

$sql = "INSERT INTO csg_segmento_social (nome_segmento_social, stat_segmento_social, desc_url_amigavel) VALUES (:nome_segmento_social, :stat_segmento_social, :desc_url_amigavel)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":nome_segmento_social", 'Segmento Teste Automatizado');
$stmt->bindValue(":stat_segmento_social", 'A');
$stmt->bindValue(":desc_url_amigavel", 'segmento_teste_automatizado');
$stmt->execute();
