<?php

require_once 'Conexao.php';
$dbh = Conexao::getInstance();

$sql = "SELECT s.* from csg_servico_relacionado s";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result != "") {
    $idServico = $result['ID_SERVICO'];
}

$stmt = $dbh->prepare("DELETE FROM csg_servico_relacionado");
$stmt->execute();
$stmt = $dbh->prepare("DELETE FROM csg_servico where id_servico = $idServico");
$stmt->execute();
