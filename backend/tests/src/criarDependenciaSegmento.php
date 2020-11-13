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

$sql = "INSERT INTO csg_servico (nome_servico, indi_servico_digital, tipo_validade_documento, stat_servico, desc_url_amigavel, id_prestador_servico, indi_servico_destaque) VALUES (:nome_servico, :indi_servico_digital, :tipo_validade_documento, :stat_servico, :desc_url_amigavel, :id_prestador_servico, :indi_servico_destaque)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":nome_servico", 'Serviço Automatizado');
$stmt->bindValue(":indi_servico_digital", 'N');
$stmt->bindValue(":tipo_validade_documento", 'P');
$stmt->bindValue(":stat_servico", 'A');
$stmt->bindValue(":desc_url_amigavel", 'servico_automatizado');
$stmt->bindValue(":id_prestador_servico", $idPrestadorServico);
$stmt->bindValue(":indi_servico_destaque", 'N');
$stmt->execute();
$idServico = $dbh->lastInsertId();

$sql = "SELECT s.* from csg_segmento_social s";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result != "") {
    $idSegmentoSocial = $result['ID_SEGMENTO_SOCIAL'];
}
$sql = "INSERT INTO csg_servico_segmento_social (id_servico, id_segmento_social) VALUES (:id_servico, :id_segmento_social)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":id_servico", $idServico);
$stmt->bindValue(":id_segmento_social", $idSegmentoSocial);
$stmt->execute();
