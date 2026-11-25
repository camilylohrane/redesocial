<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../controllers/UsuarioController.php";
require_once __DIR__ . "/../controllers/PublicacaoController.php";
require_once __DIR__ . "/../controllers/CurtidaController.php";

$rota = $_GET['rota'] ?? '';
$metodo = $_SERVER['REQUEST_METHOD'];
if ($metodo === 'POST' && ($_POST['_method'] ?? '') === 'DELETE') $metodo = 'DELETE';

if ($rota === 'usuario' && $metodo === 'POST') createUsuario($con);
elseif ($rota === 'login' && $metodo === 'POST') loginUsuario($con);
elseif ($rota === 'logout') logoutUsuario();
elseif ($rota === 'publicacao' && $metodo === 'POST') createPublicacao($con);
elseif ($rota === 'curtida' && $metodo === 'POST') createCurtida($con);
elseif ($rota === 'curtida' && $metodo === 'DELETE') deleteCurtida($con);
else { http_response_code(404); die('Rota não encontrada.'); }
