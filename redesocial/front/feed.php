<?php
session_start();
require_once __DIR__ . '/../back/config.php';
require_once __DIR__ . '/../back/models/PublicacaoModel.php';
require_once __DIR__ . '/../back/models/CurtidaModel.php';
$posts = listarPublicacoes($con);
$logado = !empty($_SESSION['id_usuario']);
?><!DOCTYPE html><html lang="pt-br"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Bloom | Feed</title><link rel="stylesheet" href="style.css"></head><body>
<header><h1 class="logo">Bloom<span>✦</span></h1><nav><a href="cadastro.php">Cadastro</a><a href="login.php">Login</a><a href="feed.php">Início</a><a href="postagem.php">Publicar</a><a href="curtidas.php">Curtidas</a><?php if($logado): ?><a href="../back/api.php?rota=logout">Sair</a><?php endif; ?></nav></header>
<main class="feed"><h2>Explore o feed ✨</h2><p><?= $logado ? 'Olá, '.htmlspecialchars($_SESSION['nome']).'! Veja os momentos compartilhados pela comunidade.' : 'Veja os momentos compartilhados pela comunidade.' ?></p>
<?php if(isset($_GET['publicado'])): ?><p class="sucesso">Publicação criada com sucesso!</p><?php endif; ?>
<?php foreach($posts as $post): $curtiu=$logado ? usuarioCurtiu($con,(int)$_SESSION['id_usuario'],(int)$post['id_publicacao']) : false; ?>
<article class="publicacao"><div class="usuario"><div><h3><?= htmlspecialchars($post['nome']) ?></h3><span>@<?= htmlspecialchars($post['username']) ?></span></div></div>
<?php if(!empty($post['imagem_url'])): ?><img class="foto-publicacao" src="<?= htmlspecialchars($post['imagem_url']) ?>" alt="Imagem da publicação"><?php endif; ?><p class="legenda"><?= nl2br(htmlspecialchars($post['legenda'])) ?></p><div class="acoes"><span>♡ <?= (int)$post['total_curtidas'] ?> curtida(s)</span><?php if($logado): ?><form action="../back/api.php?rota=curtida" method="POST" style="display:inline-flex;flex-direction:row;gap:8px;margin:0"><input type="hidden" name="id_publicacao" value="<?= (int)$post['id_publicacao'] ?>"><input type="hidden" name="acao" value="<?= $curtiu ? 'descurtir' : 'curtir' ?>"><button type="submit"><?= $curtiu ? '♥ Descurtir' : '♡ Curtir' ?></button></form><?php else: ?><a href="login.php">♡ Curtir</a><?php endif; ?></div></article>
<?php endforeach; ?><a href="postagem.php" class="botao-link botao">+ Criar publicação</a></main><footer><p>© 2026 Bloom - Conectando pessoas.</p></footer></body></html>
