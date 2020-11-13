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

$sql = "INSERT INTO csg_etapa_servico (nome_etapa_servico, info_etapa_servico, indi_etapa_possui_custo, numr_ordem_etapa_servico, servico_id_servico) VALUES (:nome_etapa_servico, :info_etapa_servico, :indi_etapa_possui_custo, :numr_ordem_etapa_servico, :servico_id_servico)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":nome_etapa_servico", 'Etapa Automatizado');
$stmt->bindValue(":info_etapa_servico", 'Etapa Automatizado');
$stmt->bindValue(":indi_etapa_possui_custo", 'N');
$stmt->bindValue(":numr_ordem_etapa_servico", '1');
$stmt->bindValue(":servico_id_servico", $idServico);
$stmt->execute();
$idEtapaServico = $dbh->lastInsertId();


$sql = "SELECT d.* from csg_documento_servico d";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result != "") {
    $idDocumentoServico = $result['ID_DOCUMENTO_SERVICO'];
}
$sql = "INSERT INTO csg_documento_etapa_servico (numr_ordem_doc_etapa_serv, id_etapa_servico, id_documento_servico) VALUES (:numr_ordem_doc_etapa_serv, :id_etapa_servico, :id_documento_servico)";
$stmt = $dbh->prepare($sql);
//, id_etapa_servico, id_documento_servico
$stmt->bindValue(":numr_ordem_doc_etapa_serv", '1');
$stmt->bindValue(":id_etapa_servico", $idEtapaServico);
$stmt->bindValue(":id_documento_servico", $idDocumentoServico);
$stmt->execute();
