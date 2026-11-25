<?php
require_once __DIR__ . "/../models/CurtidaModel.php";

function createCurtida($con){
    if (!isset($_SESSION['id_usuario'])) { header("Location: ../front/login.php"); exit; }
    $idPublicacao = filter_input(INPUT_POST, 'id_publicacao', FILTER_VALIDATE_INT);
    if (!$idPublicacao) die("Publicação inválida.");
    inserirCurtida($con, ['id_usuario'=>$_SESSION['id_usuario'], 'id_publicacao'=>$idPublicacao]);
    header("Location: ../front/feed.php");
    exit;
}

function deleteCurtida($con){
    if (!isset($_SESSION['id_usuario'])) { header("Location: ../front/login.php"); exit; }
    $idPublicacao = filter_input(INPUT_POST, 'id_publicacao', FILTER_VALIDATE_INT);
    if ($idPublicacao) removerCurtidaPorUsuario($con, $_SESSION['id_usuario'], $idPublicacao);
    header("Location: ../front/feed.php");
    exit;
}
