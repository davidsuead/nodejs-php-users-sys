<?php
require_once 'Conexao.php';
$dbh = Conexao::getInstance();
$stmt = $dbh->prepare("DELETE FROM csg_registro_atividade_sistema");
$stmt->execute();
