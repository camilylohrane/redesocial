<?php
function inserirPublicacao(PDO $con, int $idUsuario, string $legenda, ?string $imagemUrl): int {
    $stmt = $con->prepare('INSERT INTO publicacoes (id_usuario, legenda, imagem_url) VALUES (?, ?, ?)');
    $stmt->execute([$idUsuario, $legenda, $imagemUrl]);
    return (int)$con->lastInsertId();
}

function listarPublicacoes(PDO $con): array {
    $sql = 'SELECT p.id_publicacao, p.id_usuario, p.legenda, p.imagem_url, p.data_publicacao,
                   u.nome, u.username,
                   (SELECT COUNT(*) FROM curtidas c WHERE c.id_publicacao = p.id_publicacao) AS total_curtidas
            FROM publicacoes p
            INNER JOIN usuarios u ON u.id_usuario = p.id_usuario
            ORDER BY p.data_publicacao DESC, p.id_publicacao DESC';
    return $con->query($sql)->fetchAll();
}
