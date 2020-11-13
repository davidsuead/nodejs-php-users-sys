<?php

require_once 'Conexao.php';
$dbh = Conexao::getInstance();
$stmt = $dbh->prepare("DELETE FROM csg_servico_categoria");
$stmt->execute();
$stmt = $dbh->prepare("DELETE FROM csg_servico");
$stmt->execute();
$stmt = $dbh->prepare("DELETE FROM csg_prestador_servico");
$stmt->execute();
