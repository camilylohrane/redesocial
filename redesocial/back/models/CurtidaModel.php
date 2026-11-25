<?php
function usuarioCurtiu(PDO $con, int $idUsuario, int $idPublicacao): bool {
    $stmt = $con->prepare('SELECT 1 FROM curtidas WHERE id_usuario = ? AND id_publicacao = ? LIMIT 1');
    $stmt->execute([$idUsuario, $idPublicacao]);
    return (bool)$stmt->fetchColumn();
}

function inserirCurtida(PDO $con, int $idUsuario, int $idPublicacao): int {
    if (usuarioCurtiu($con, $idUsuario, $idPublicacao)) return 0;
    $stmt = $con->prepare('INSERT INTO curtidas (id_usuario, id_publicacao) VALUES (?, ?)');
    $stmt->execute([$idUsuario, $idPublicacao]);
    return (int)$con->lastInsertId();
}

function removerCurtidaDoUsuario(PDO $con, int $idUsuario, int $idPublicacao): void {
    $stmt = $con->prepare('DELETE FROM curtidas WHERE id_usuario = ? AND id_publicacao = ?');
    $stmt->execute([$idUsuario, $idPublicacao]);
}
