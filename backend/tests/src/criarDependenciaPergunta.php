<?php

require_once 'Conexao.php';
$dbh = Conexao::getInstance();
$sql = "INSERT INTO csg_faq (desc_pergunta_formulada, info_resposta_pergunta, numr_ordem_impressao_coluna, stat_faq) VALUES (:desc_pergunta_formulada, :info_resposta_pergunta, :numr_ordem_impressao_coluna, :stat_faq)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":desc_pergunta_formulada", 'Automatizado 1');
$stmt->bindValue(":info_resposta_pergunta", 'Automatizado 1');
$stmt->bindValue(":numr_ordem_impressao_coluna", '1');
$stmt->bindValue(":stat_faq", 'A');
$stmt->execute();

$sql = "INSERT INTO csg_faq (desc_pergunta_formulada, info_resposta_pergunta, numr_ordem_impressao_coluna, stat_faq) VALUES (:desc_pergunta_formulada, :info_resposta_pergunta, :numr_ordem_impressao_coluna, :stat_faq)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":desc_pergunta_formulada", 'Automatizado 2');
$stmt->bindValue(":info_resposta_pergunta", 'Automatizado 2');
$stmt->bindValue(":numr_ordem_impressao_coluna", '2');
$stmt->bindValue(":stat_faq", 'A');
$stmt->execute();
