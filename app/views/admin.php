<?php
session_start();

// Se não estiver logado como administrador, redireciona para o login
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<?php
include '../../config/conexao.php';
include 'partials/header.php';

// Buscar todos os questionários do banco de dados ordenados pela data correta
try {
    $stmt = $pdo->query("SELECT * FROM questionarios ORDER BY data_criacao DESC");
    $questionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $questionarios = [];
    $erro = $e->getMessage();
}
?>

<main class="mx-auto flex w-full max-w-4xl flex-col gap-6 px-4 py-8 sm:py-12 text-left items-start">
    <!-- Cabeçalho da Página de Administração -->
    <!-- Cabeçalho do Painel com o botão de Sair alinhado à direita -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold">Painel de Administração</h1>
        <p class="text-sm text-gray-400">Faça a gestão dos questionários criados e relatórios de respostas.</p>
    </div>
    
    <a href="logout.php" class="inline-flex items-center gap-2 bg-[#2a0808] hover:bg-[#3d0c0c] border border-red-900/50 text-red-400 hover:text-red-300 text-xs font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
        </svg>
        <span>Sair da Administração</span>
    </a>
</div>

    <?php if (isset($erro)): ?>
        <div class="w-full rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 shadow-sm">
            Erro ao carregar questionários: <?php echo htmlspecialchars($erro); ?>
        </div>
    <?php endif; ?>

    <!-- lista de questionarios -->
    <div class="flex flex-col gap-4 w-full">
        <h2 class="text-xl font-bold text-slate-900">Questionários Existentes</h2>

        <?php if (empty($questionarios)): ?>
            <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-500 shadow-sm w-full">
                Nenhum questionário registrado no momento.
            </div>
        <?php else: ?>

            <div class="grid gap-4 sm:grid-cols-2 w-full">
                <?php foreach ($questionarios as $questionario): ?>

                    <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        
                        <!-- Topo do Cartão: Título, Status e Descrição -->
                        <div class="flex flex-col text-left">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <h3 class="font-bold text-slate-900 text-lg"><?php echo htmlspecialchars($questionario['titulo']); ?></h3>
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase shrink-0 
                                    <?php echo $questionario['status'] === 'publicado' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200'; ?>">
                                    <?php echo htmlspecialchars($questionario['status']); ?>
                                </span>
                            </div>
                            <p class="text-sm text-slate-500 leading-relaxed"><?php echo htmlspecialchars($questionario['descricao'] ?? 'Sem descrição.'); ?></p>
                        </div>

                        <!-- Base do Cartão: Público-alvo e Botão  -->
                        <div class="flex flex-col gap-4 mt-6">
                            <span class="text-xs text-slate-400">Público-alvo: <?php echo htmlspecialchars($questionario['publico_alvo']); ?></span>

                            <div class="flex items-center">
                                <a href="relatorio.php?id=<?php echo $questionario['id']; ?>" style="background-color: var(--primary);" class="inline-flex items-center gap-1.5 hover:opacity-90 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-opacity shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Ver Relatório
                                </a>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'partials/footer.php'; ?>