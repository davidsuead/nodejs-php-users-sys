<?php

require_once 'Conexao.php';
$dbh = Conexao::getInstance();

$sql = "SELECT s.* from csg_servico s";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result != "") {
    $idServicoAssociado = $result['ID_SERVICO'];
}

$sql = "SELECT p.* from csg_prestador_servico p";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result != "") {
    $idPrestadorServico = $result['ID_PRESTADOR_SERVICO'];
}


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


$sql = "INSERT INTO csg_servico_relacionado (numr_ordem_servico_relaci, id_servico, id_servico_associado) VALUES (:numr_ordem_servico_relaci, :id_servico, :id_servico_associado)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":numr_ordem_servico_relaci", 1);
$stmt->bindValue(":id_servico", $idServico);
$stmt->bindValue(":id_servico_associado", $idServicoAssociado);
$stmt->execute();
