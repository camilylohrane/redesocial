<?php
require_once __DIR__ . "/../models/UsuarioModel.php";

function receberPost(){
    return $_POST;
}

function createUsuario($con){
    $dados = receberPost();
    foreach (["nome", "username", "email", "senha"] as $campo) {
        if (!isset($dados[$campo]) || trim($dados[$campo]) === '') {
            http_response_code(400);
            die("O campo {$campo} é obrigatório.");
        }
    }

    if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) die("E-mail inválido.");
    if (buscarUsuarioPorEmail($con, $dados['email'])) die("Este e-mail já está cadastrado.");

    try {
        inserirUsuario($con, $dados);
        header("Location: ../front/login.php?cadastro=sucesso");
        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        die("Não foi possível cadastrar: " . htmlspecialchars($e->getMessage()));
    }
}

function loginUsuario($con){
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $usuario = buscarUsuarioPorEmail($con, $email);

    if (!$usuario || !password_verify($senha, $usuario['senha'])) {
        header("Location: ../front/login.php?erro=1");
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['id_usuario'] = (int)$usuario['id_usuario'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['username'] = $usuario['username'];
    header("Location: ../front/feed.php");
    exit;
}

function logoutUsuario(){
    session_unset();
    session_destroy();
    header("Location: ../front/login.php");
    exit;
}
