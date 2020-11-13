<?php

require_once 'Conexao.php';
$dbh = Conexao::getInstance();

$sql = "INSERT INTO csg_registro_atividade_sistema (desc_titulo_log, info_acao_usuario, numr_ip_atividade_sistema, numr_cpf_resp_acao) VALUES (:desc_titulo_log, :info_acao_usuario, :numr_ip_atividade_sistema, :numr_cpf_resp_acao)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":desc_titulo_log", 'Teste Automatizado');
$stmt->bindValue(":info_acao_usuario", 'Teste Automatizado');
$stmt->bindValue(":numr_ip_atividade_sistema", '127.0.0.1');
$stmt->bindValue(":numr_cpf_resp_acao", '01234567890');
$stmt->execute();
