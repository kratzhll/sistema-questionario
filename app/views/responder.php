<?php
include '../../config/conexao.php';
include 'partials/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo '<main class="mx-auto flex w-full max-w-2xl flex-col items-start gap-4 px-4 py-14">
            <h1 class="text-2xl font-bold text-slate-900">Questionário inválido</h1>
            <a href="index.php" class="font-bold text-blue-900 underline underline-offset-4">Ver questionários disponíveis</a>
          </main>';
    include 'footer.php';
    exit;
}

try {
    $stmtQ = $pdo->prepare("SELECT * FROM questionarios WHERE id = ? AND status = 'publicado'");
    $stmtQ->execute([$id]);
    $questionario = $stmtQ->fetch(PDO::FETCH_ASSOC);

    if (!$questionario) {
        echo '<main class="mx-auto flex w-full max-w-2xl flex-col items-start gap-4 px-4 py-14">
                <h1 class="text-2xl font-bold text-slate-900">Questionário indisponível</h1>
                <p class="text-slate-600">Este questionário foi encerrado ou não existe.</p>
                <a href="index.php" class="font-bold text-blue-900 underline underline-offset-4">Ver questionários disponíveis</a>
              </main>';
        include 'footer.php';
        exit;
    }

    $stmtQuest = $pdo->prepare("SELECT * FROM questoes WHERE questionario_id = ? AND status = 1 ORDER BY ordem ASC");
    $stmtQuest->execute([$id]);
    $questoes = $stmtQuest->fetchAll(PDO::FETCH_ASSOC);
    $totalQuestoes = count($questoes);

} catch (PDOException $e) {
    echo "Erro ao carregar questionário: " . $e->getMessage();
    exit;
}
?>

<main class="mx-auto flex w-full max-w-3xl flex-col gap-6 px-4 py-8 sm:py-12">
    <!-- Link Voltar -->
    <a href="index.php" class="flex items-center gap-1.5 self-start text-sm font-bold text-blue-900 underline-offset-4 hover:underline">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Todos os questionários
    </a>

    <!-- Cabeçalho do Questionário -->
    <header class="flex flex-col gap-3">
        <h1 class="text-3xl font-bold text-slate-900"><?php echo htmlspecialchars($questionario['titulo']); ?></h1>
        <?php if (!empty($questionario['descricao'])): ?>
            <p class="text-lg text-slate-600"><?php echo htmlspecialchars($questionario['descricao']); ?></p>
        <?php endif; ?>
        <p class="flex items-center gap-1.5 text-sm text-slate-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Público-alvo: <?php echo htmlspecialchars($questionario['publico_alvo']); ?></span>
        </p>
    </header>

    <!-- Barra de Progresso Fixa / Sticky (Idêntica ao v0) -->
    <div class="sticky top-0 z-10 -mx-4 flex flex-col gap-2 border-b border-slate-200 bg-white/95 px-4 py-3 backdrop-blur sm:mx-0 sm:rounded-xl sm:border sm:shadow-sm">
        <div class="flex items-center justify-between text-sm">
            <span class="font-bold text-slate-900">Seu progresso</span>
            <span id="progresso-texto" class="text-slate-500">0 de <?php echo $totalQuestoes; ?> respondidas</span>
        </div>
        <div class="h-2 overflow-hidden rounded-full bg-slate-100">
            <div id="barra-progresso" class="h-full rounded-full bg-blue-900 transition-all" style="width: 0%;"></div>
        </div>
    </div>

    <p class="text-sm text-slate-500">
        Questões marcadas com <span class="font-bold text-red-500">*</span> e o rótulo <span class="font-bold text-slate-900">Obrigatória</span> precisam ser respondidas.
    </p>

    <!-- Formulário de Respostas -->
    <form action="../controllers/salvar_resposta.php" method="POST">
        <input type="hidden" name="questionario_id" value="<?php echo $id; ?>">

        <ol class="flex flex-col gap-5">
            <?php foreach ($questoes as $index => $q): ?>
                <li class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
                    
                    <!-- Cabeçalho da Questão -->
                    <div class="flex flex-wrap items-start justify-between gap-2">
                        <span class="text-lg font-bold text-slate-900">
                            <span class="text-slate-400"><?php echo ($index + 1); ?>. </span>
                            <?php echo htmlspecialchars($q['enunciado']); ?>
                            <?php if ($q['obrigatorio']): ?>
                                <span class="text-red-500">*</span>
                            <?php endif; ?>
                        </span>
                        <?php if ($q['obrigatorio']): ?>
                            <span class="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-xs font-bold text-slate-800">Obrigatória</span>
                        <?php else: ?>
                            <span class="rounded-full px-2.5 py-0.5 text-xs text-slate-400">Opcional</span>
                        <?php endif; ?>
                    </div>

                    <?php
                    $stmtAlt = $pdo->prepare("SELECT * FROM alternativas WHERE questao_id = ? ORDER BY ordem ASC");
                    $stmtAlt->execute([$q['id']]);
                    $alternativas = $stmtAlt->fetchAll(PDO::FETCH_ASSOC);
                    $escala = ($q['tipo_resposta'] === 'escala');
                    ?>

                    <?php if ($escala || $q['tipo_resposta'] === 'multipla_escolha'): ?>
                        <!-- Grelha de Alternativas / Escala -->
                        <div class="grid gap-2 <?php echo $escala ? 'grid-cols-1 sm:grid-cols-5' : 'grid-cols-1 sm:grid-cols-2'; ?>">
                            <?php foreach ($alternativas as $i => $alt): ?>
                                <label class="group relative flex cursor-pointer items-center gap-3 rounded-xl border-2 border-slate-200 bg-white p-3 transition-colors hover:border-blue-900/50 hover:bg-slate-50 has-checked:border-blue-900 has-checked:bg-blue-50/40 <?php echo $escala ? 'sm:flex-col sm:justify-start sm:px-2 sm:py-4 sm:text-center' : ''; ?>">
                                    <input 
                                        type="radio" 
                                        name="resposta_<?php echo $q['id']; ?>" 
                                        value="<?php echo $alt['id']; ?>" 
                                        <?php echo $q['obrigatorio'] ? 'required' : ''; ?> 
                                        class="sr-only responder-input" 
                                        data-questao="<?php echo $q['id']; ?>">
                                    
                                    <?php if ($escala): ?>
                                        <span class="flex size-9 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 text-sm font-bold text-slate-700 group-has-checked:border-blue-900 group-has-checked:bg-blue-900 group-has-checked:text-white transition-colors">
                                            <?php echo ($alt['valor'] ?? $i + 1); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="flex size-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 group-has-checked:border-blue-900">
                                            <span class="size-2.5 rounded-full bg-blue-900 opacity-0 group-has-checked:opacity-100 transition-opacity"></span>
                                        </span>
                                    <?php endif; ?>

                                    <span class="text-base leading-snug text-slate-700"><?php echo htmlspecialchars($alt['texto']); ?></span>

                                    <!-- Ícone de Check do v0 -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-2 right-2 hidden size-4 text-blue-900 group-has-checked:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </label>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif ($q['tipo_resposta'] === 'texto'): ?>
                        <textarea 
                            name="resposta_<?php echo $q['id']; ?>" 
                            rows="4" 
                            maxLength="2000"
                            placeholder="Escreva aqui a sua resposta" 
                            <?php echo $q['obrigatorio'] ? 'required' : ''; ?> 
                            class="w-full rounded-xl border border-slate-300 bg-white p-3.5 text-base text-slate-900 placeholder:text-slate-400 focus-visible:border-blue-900 focus-visible:ring-3 focus-visible:ring-blue-900/20 focus-visible:outline-none resize-y responder-input" 
                            data-questao="<?php echo $q['id']; ?>"></textarea>
                    <?php endif; ?>

                </li>
            <?php endforeach; ?>
        </ol>

        <!-- Botões de Ação Rodapé -->
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between pt-4">
            <a href="index.php" class="text-sm font-bold text-blue-900 underline-offset-4 hover:underline">
                Voltar sem enviar
            </a>
            <button type="submit" class="h-12 px-8 text-base bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-xl transition-colors shadow-sm cursor-pointer">
                Enviar respostas
            </button>
        </div>
    </form>
</main>

<!-- Script para interatividade da barra de progresso em tempo real -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const total = <?php echo $totalQuestoes; ?>;
    const inputs = document.querySelectorAll('.responder-input');
    const barra = document.getElementById('barra-progresso');
    const textoProgresso = document.getElementById('progresso-texto');

    function atualizarProgressoVisual() {
        const respondidasSet = new Set();

        inputs.forEach(input => {
            const label = input.closest('label');
            if (!label) return;

            if (input.type === 'radio' && input.checked) {
                respondidasSet.add(input.dataset.questao);
                
                // Destaca visualmente o cartão selecionado
                label.classList.remove('border-slate-200', 'bg-white');
                label.classList.add('border-blue-900', 'bg-blue-50/40');
            } else if (input.type === 'radio') {
                // Remove o destaque dos não selecionados do mesmo grupo
                const name = input.name;
                const checkedInGroup = document.querySelector(`input[name="${name}"]:checked`);
                if (!checkedInGroup || checkedInGroup !== input) {
                    label.classList.remove('border-blue-900', 'bg-blue-50/40');
                    label.classList.add('border-slate-200', 'bg-white');
                }
            } else if (input.tagName === 'TEXTAREA' && input.value.trim().length > 0) {
                respondidasSet.add(input.dataset.questao);
            }
        });

        const count = respondidasSet.size;
        const percentual = total > 0 ? Math.round((count / total) * 100) : 0;

        barra.style.width = percentual + '%';
        textoProgresso.textContent = `${count} de ${total} respondidas`;
    }

    inputs.forEach(input => {
        input.addEventListener('change', atualizarProgressoVisual);
        input.addEventListener('input', atualizarProgressoVisual);
    });
});
</script>

<?php include 'partials/footer.php'; ?>