<?php

require_once 'Conexao.php';
$dbh = Conexao::getInstance();
$sql = "INSERT INTO csg_usuario (numr_cpf_usuario) VALUES (:numr_cpf_usuario)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":numr_cpf_usuario", '01234567890');
$stmt->execute();
$idUsuario = $dbh->lastInsertId();

$sql = "SELECT p.* from csg_prestador_servico p";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result != "") {
    $idPrestadorServico = $result['ID_PRESTADOR_SERVICO'];
}
$sql = "INSERT INTO csg_usuario_prestador_servico (id_usuario, id_prestador_servico) VALUES (:id_usuario, :id_prestador_servico)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":id_usuario", $idUsuario);
$stmt->bindValue(":id_prestador_servico", $idPrestadorServico);
$stmt->execute();
