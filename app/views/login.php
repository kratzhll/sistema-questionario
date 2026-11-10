<?php
session_start();

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha_digitada = $_POST['senha'] ?? '';
    
    // Defina aqui a senha da administração do projeto
    $senha_mestra = 'Qwerty!88'; 

    if ($senha_digitada === $senha_mestra) {
        $_SESSION['admin_logado'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $erro = 'Senha incorreta!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login Administração — Spooky Sweets</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0c0a09] text-white flex items-center justify-center h-screen">
    <div class="bg-[#171717] border border-[#262626] p-8 rounded-2xl w-96 shadow-lg">
        <h2 class="text-2xl font-bold mb-2 text-[#f97316]">Administração</h2>
        <p class="text-sm text-gray-400 mb-6">Apenas para gestores do sistema.</p>

        <?php if (!empty($erro)): ?>
            <div class="bg-red-900/50 border border-red-700 text-red-200 text-sm p-3 rounded-xl mb-4">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-xs text-gray-400 mb-1 uppercase font-semibold">Senha de Acesso</label>
                <input type="password" name="senha" required class="w-full bg-[#121212] border border-[#262626] rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-[#f97316]">
            </div>
            <button type="submit" class="w-full bg-[#f97316] hover:bg-[#ea580c] text-white font-bold py-2.5 rounded-xl transition-colors">
                Entrar no Painel
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="index.php" class="text-xs text-gray-500 hover:text-gray-300">← Voltar para a página inicial</a>
        </div>
    </div>
</body>
</html>