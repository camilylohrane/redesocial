<?php
function inserirUsuario(PDO $con, array $dados): int {
    $stmt = $con->prepare('INSERT INTO usuarios (nome, username, email, senha) VALUES (?, ?, ?, ?)');
    $stmt->execute([$dados['nome'], $dados['username'], $dados['email'], $dados['senha']]);
    return (int)$con->lastInsertId();
}

function buscarUsuarioPorEmail(PDO $con, string $email): ?array {
    $stmt = $con->prepare('SELECT * FROM usuarios WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    return $usuario ?: null;
}

function buscarUsuarioPorId(PDO $con, int $id): ?array {
    $stmt = $con->prepare('SELECT * FROM usuarios WHERE id_usuario = ? LIMIT 1');
    $stmt->execute([$id]);
    $usuario = $stmt->fetch();
    return $usuario ?: null;
}
