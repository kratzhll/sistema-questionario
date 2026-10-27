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

<main class="mx-auto flex w-full max-w-3xl flex-col gap-6 px-4 py-8 sm:py-12">
    <!-- Cabeçalho da Página de Administração -->
    <div class="flex flex-col gap-2">
        <h1 class="text-3xl font-bold text-slate-900">Painel de Administração</h1>
        <p class="text-slate-600">Faça a gestão dos questionários criados e aceda aos relatórios de respostas.</p>
    </div>

    <?php if (isset($erro)): ?>
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 shadow-sm">
            Erro ao carregar questionários: <?php echo htmlspecialchars($erro); ?>
        </div>
    <?php endif; ?>

    <!-- Lista de Questionários -->
    <div class="flex flex-col gap-4">
        <h2 class="text-xl font-bold text-slate-900">Questionários Existentes</h2>

        <?php if (empty($questionarios)): ?>
            <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-500 shadow-sm">
                Nenhum questionário registado no momento.
            </div>
        <?php else: ?>
            <div class="grid gap-4">
                <?php foreach ($questionarios as $questionario): ?>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-slate-900 text-lg"><?php echo htmlspecialchars($questionario['titulo']); ?></h3>
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase 
                                    <?php echo $questionario['status'] === 'publicado' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200'; ?>">
                                    <?php echo htmlspecialchars($questionario['status']); ?>
                                </span>
                            </div>
                            <p class="text-sm text-slate-500"><?php echo htmlspecialchars($questionario['descricao'] ?? 'Sem descrição.'); ?></p>
                            <span class="text-xs text-slate-400 mt-1">Público-alvo: <?php echo htmlspecialchars($questionario['publico_alvo']); ?></span>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="relatorio.php?id=<?php echo $questionario['id']; ?>" class="inline-flex items-center gap-1.5 bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Ver Relatório
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'partials/footer.php'; ?>