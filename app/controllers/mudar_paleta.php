<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paleta = (isset($_POST['paleta']) && $_POST['paleta'] === 'b') ? 'b' : 'a';
    
    // Define o cookie por 1 ano com a multiplicação corrigida
    setcookie('paleta', $paleta, [
        'expires' => time() + (86400 * 365),
        'path' => '/',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    // Redireciona de volta para a página anterior
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../views/index.php';
    header("Location: " . $redirect);
    exit;
} else {
    header("Location: ../views/index.php");
    exit;
}
?>