<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../config/conexao.php';
include 'partials/header.php';

try {
    $stmt = $pdo->query("SELECT * FROM questionarios WHERE status = 'publicado'");
    $questionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $questionarios = [];
}
?>

<main class="mx-auto flex w-full max-w-4xl flex-col gap-6 px-4 py-8 sm:py-12 text-left items-start">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
        <div class="flex flex-col gap-2 w-full items-start">
            <span class="text-xs font-bold uppercase tracking-wider" style="color: var(--primary);">Pesquisas de satisfação</span>
            <h1 class="text-3xl font-bold text-slate-900">Sua opinião ajuda a melhorar o que fazemos</h1>
            <p class="text-slate-600">
                Escolha um questionário abaixo e responda no seu ritmo. As respostas são anônimas.
            </p>
        </div>

        <!-- Botão Novo Questionário (Apenas visível para o Admin) -->
        <?php if (isset($_SESSION['admin_logado']) && $_SESSION['admin_logado'] === true): ?>
            <a href="criar.php" style="background-color: var(--primary);" class="inline-flex items-center justify-center gap-2 !text-white font-semibold text-xs px-4 py-2.5 rounded-xl transition-opacity hover:opacity-90 shadow-sm shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Adicionar Questionário
            </a>
        <?php endif; ?>
    </div>

    <div class="flex flex-col gap-4 w-full">
        <h2 class="text-xl font-bold text-slate-900">
            Questionários disponíveis
        </h2>

        <?php if (empty($questionarios)): ?>
            <p class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500 w-full shadow-sm">
                Nenhum questionário está aberto para respostas no momento.
            </p>
        <?php else: ?>
            <!-- Grelha com 2 colunas perfeitamente alinhada com o max-w-4xl do header -->
            <div class="grid gap-4 sm:grid-cols-2 w-full">
                <?php foreach ($questionarios as $q): ?>
                    <!-- flex flex-col justify-between garante o alinhamento perfeito na horizontal e vertical -->
                    <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        
                        <!-- Topo do Cartão: Título e Descrição -->
                        <div class="flex flex-col text-left">
                            <h3 class="text-lg font-bold text-slate-900 mb-2"><?php echo htmlspecialchars($q['titulo']); ?></h3>
                            <p class="text-slate-500 text-sm leading-relaxed"><?php echo htmlspecialchars($q['descricao']); ?></p>
                        </div>
                        
                        <!-- Base do Cartão: Público-alvo e Botões -->
                        <div class="flex flex-col gap-4 mt-6">
                            <span class="text-xs text-slate-400">Público-alvo: <?php echo htmlspecialchars($q['publico_alvo']); ?></span>
                            
                            <div class="flex items-center justify-between gap-2 pt-2 flex-wrap">
                                <a href="responder.php?id=<?php echo $q['id']; ?>" style="background-color: var(--primary);" class="inline-flex items-center justify-center gap-2 text-white font-medium text-xs px-4 py-2.5 rounded-xl transition-opacity hover:opacity-90 shadow-sm">
                                    Responder 
                                    <span class="sr-only">: <?php echo htmlspecialchars($q['titulo']); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>

                                <!-- Botões de Editar e Excluir (Apenas visíveis para o Admin) -->
                                <?php if (isset($_SESSION['admin_logado']) && $_SESSION['admin_logado'] === true): ?>
                                    <div class="flex items-center gap-2">
                                        <a href="editar.php?id=<?php echo $q['id']; ?>" class="text-xs font-semibold px-3 py-2 bg-neutral-800 hover:bg-neutral-700 text-neutral-200 border border-neutral-700 rounded-xl transition-colors">
                                            Editar
                                        </a>
                                        <a href="excluir.php?id=<?php echo $q['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este questionário?');" class="text-xs font-semibold px-3 py-2 bg-[#2a0808] hover:bg-[#3d0c0c] border border-red-900/50 text-red-400 hover:text-red-300 rounded-xl transition-colors">
                                            Excluir
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'partials/footer.php'; ?>