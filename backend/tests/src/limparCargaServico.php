<?php

require_once 'Conexao.php';
$dbh = Conexao::getInstance();
$stmt = $dbh->prepare("DELETE FROM csg_usuario_prestador_servico");
$stmt->execute();
$stmt = $dbh->prepare("DELETE FROM csg_usuario");
$stmt->execute();
$stmt = $dbh->prepare("DELETE FROM csg_prestador_servico");
$stmt->execute();
$stmt = $dbh->prepare("DELETE FROM csg_categoria_servico");
$stmt->execute();
$stmt = $dbh->prepare("DELETE FROM csg_segmento_social");
$stmt->execute();
