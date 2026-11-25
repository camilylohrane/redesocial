<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/models/UsuarioModel.php';
require_once __DIR__ . '/models/PublicacaoModel.php';
require_once __DIR__ . '/models/CurtidaModel.php';

$rota = $_GET['rota'] ?? '';

function voltar(string $pagina, string $mensagem = ''): void {
    $url = '../front/' . $pagina;
    if ($mensagem !== '') $url .= '?erro=' . urlencode($mensagem);
    header('Location: ' . $url);
    exit;
}

if ($rota === 'usuario' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || $username === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $senha === '') {
        voltar('cadastro.php', 'Preencha todos os campos corretamente.');
    }

    if (buscarUsuarioPorEmail($con, $email)) {
        voltar('cadastro.php', 'Este e-mail já está cadastrado.');
    }

    $stmt = $con->prepare('SELECT id_usuario FROM usuarios WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    if ($stmt->fetch()) voltar('cadastro.php', 'Este username já está cadastrado.');

    inserirUsuario($con, [
        'nome' => $nome,
        'username' => $username,
        'email' => $email,
        'senha' => password_hash($senha, PASSWORD_DEFAULT)
    ]);
    header('Location: ../front/login.php?sucesso=1');
    exit;
}

if ($rota === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $usuario = buscarUsuarioPorEmail($con, $email);

    if (!$usuario) voltar('login.php', 'E-mail ou senha incorretos.');

    $valida = password_verify($senha, $usuario['senha']);
    // Compatibilidade com o usuário antigo que foi cadastrado com senha em texto simples.
    if (!$valida && hash_equals((string)$usuario['senha'], (string)$senha)) {
        $novaSenha = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $con->prepare('UPDATE usuarios SET senha = ? WHERE id_usuario = ?');
        $stmt->execute([$novaSenha, $usuario['id_usuario']]);
        $valida = true;
    }

    if (!$valida) voltar('login.php', 'E-mail ou senha incorretos.');

    $_SESSION['id_usuario'] = (int)$usuario['id_usuario'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['username'] = $usuario['username'];
    header('Location: ../front/feed.php');
    exit;
}

if ($rota === 'logout') {
    session_unset();
    session_destroy();
    header('Location: ../front/login.php');
    exit;
}

if ($rota === 'publicacao' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_SESSION['id_usuario'])) voltar('login.php', 'Faça login para publicar.');
    $legenda = trim($_POST['legenda'] ?? '');
    $imagem = trim($_POST['imagem_url'] ?? '');
    if ($legenda === '') voltar('postagem.php', 'A legenda é obrigatória.');
    inserirPublicacao($con, (int)$_SESSION['id_usuario'], $legenda, $imagem !== '' ? $imagem : null);
    header('Location: ../front/feed.php?publicado=1');
    exit;
}

if ($rota === 'curtida' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_SESSION['id_usuario'])) voltar('login.php', 'Faça login para curtir.');
    $idPublicacao = filter_var($_POST['id_publicacao'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if (!$idPublicacao) voltar('feed.php', 'Publicação inválida.');

    $acao = $_POST['acao'] ?? 'curtir';
    if ($acao === 'descurtir') {
        removerCurtidaDoUsuario($con, (int)$_SESSION['id_usuario'], (int)$idPublicacao);
    } else {
        inserirCurtida($con, (int)$_SESSION['id_usuario'], (int)$idPublicacao);
    }
    header('Location: ../front/feed.php');
    exit;
}

header('Location: ../front/feed.php');
exit;
