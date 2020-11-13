<?php

require_once 'Conexao.php';
$dbh = Conexao::getInstance();
$sql = "INSERT INTO csg_prestador_servico (sigl_prestador_servico, nome_prestador_servico, info_prestador_servico, desc_url_amigavel, stat_prestador_servico) VALUES (:sigl_prestador_servico, :nome_prestador_servico, :info_prestador_servico, :desc_url_amigavel, :stat_prestador_servico)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":sigl_prestador_servico", 'OR1');
$stmt->bindValue(":nome_prestador_servico", 'Órgão Teste Automatizado');
$stmt->bindValue(":info_prestador_servico", 'Órgão Teste Automatizado');
$stmt->bindValue(":desc_url_amigavel", 'orgao_teste_automatizado');
$stmt->bindValue(":stat_prestador_servico", 'A');
$stmt->execute();