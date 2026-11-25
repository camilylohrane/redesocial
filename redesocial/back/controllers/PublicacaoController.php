<?php
require_once __DIR__ . "/../models/PublicacaoModel.php";

function createPublicacao($con){
    if (!isset($_SESSION['id_usuario'])) { header("Location: ../front/login.php"); exit; }
    $legenda = trim($_POST['legenda'] ?? '');
    $imagem = trim($_POST['imagem_url'] ?? '');
    if ($legenda === '') die("A legenda é obrigatória.");

    inserirPublicacao($con, [
        'id_usuario' => $_SESSION['id_usuario'],
        'legenda' => $legenda,
        'imagem_url' => $imagem !== '' ? $imagem : null
    ]);
    header("Location: ../front/feed.php?publicado=1");
    exit;
}
