<?php
<<<<<<< HEAD
session_start();

// Se não estiver logado como administrador, redireciona para o login
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<?php
=======
>>>>>>> 882ee90464bf088eeaa87ca0bcce20f099714037
include '../../config/conexao.php';
include 'partials/header.php';

$questionario_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($questionario_id <= 0) {
    echo '<main class="mx-auto flex w-full max-w-3xl flex-col gap-4 px-4 py-10">
            <h1 class="text-2xl font-bold text-slate-900">Questionário não especificado</h1>
            <a href="admin.php" style="color: var(--primary);" class="font-bold underline">Voltar à Administração</a>
          </main>';
    include 'partials/footer.php';
    exit;
}

try {
    // 1. Busca os dados do questionário
    $stmtQ = $pdo->prepare("SELECT * FROM questionarios WHERE id = ?");
    $stmtQ->execute([$questionario_id]);
    $questionario = $stmtQ->fetch(PDO::FETCH_ASSOC);

    if (!$questionario) {
        echo "Questionário não encontrado.";
        exit;
    }

    // 2. Conta o total de participações (respostas submetidas)
    $stmtTotal = $pdo->prepare("SELECT COUNT(*) as total FROM participacoes WHERE questionario_id = ?");
    $stmtTotal->execute([$questionario_id]);
    $totalParticipacoes = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // 3. Busca as questões do questionário
    $stmtQuest = $pdo->prepare("SELECT * FROM questoes WHERE questionario_id = ? ORDER BY ordem ASC");
    $stmtQuest->execute([$questionario_id]);
    $questoes = $stmtQuest->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro ao gerar relatório: " . $e->getMessage();
    exit;
}
?>

<main class="mx-auto flex w-full max-w-3xl flex-col gap-8 px-4 py-8 sm:py-12">
    <!-- Link de retorno -->
    <a href="admin.php" style="color: var(--primary);" class="flex items-center gap-1.5 self-start text-sm font-bold underline-offset-4 hover:underline">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Voltar para a Administração
    </a>

    <!-- Cabeçalho do Relatório -->
    <header class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Relatório de Resultados</span>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1"><?php echo htmlspecialchars($questionario['titulo']); ?></h1>
            </div>
<<<<<<< HEAD
            <!-- Caixa de respostas com número e texto em laranja -->
            <div class="flex items-center gap-2 rounded-xl px-4 py-3 border shadow-sm" style="background-color: #171717; border-color: #262626; color: #ffffff;">
                <span class="text-2xl font-bold" style="color: #f97316;"><?php echo $totalParticipacoes; ?></span>
                <span class="text-xs font-semibold uppercase" style="color: #f97316;">Resposta(s) Enviadas</span>
=======
            <!-- Caixa de respostas dinâmica baseada na paleta -->
            <div class="flex items-center gap-2 rounded-xl px-4 py-3 border shadow-sm" style="background-color: color-mix(in srgb, var(--primary) 8%, white); border-color: color-mix(in srgb, var(--primary) 20%, white); color: var(--primary);">
                <span class="text-2xl font-bold"><?php echo $totalParticipacoes; ?></span>
                <span class="text-xs font-semibold uppercase">Respostas Submetidas</span>
>>>>>>> 882ee90464bf088eeaa87ca0bcce20f099714037
            </div>
        </div>
    </header>

    <!-- Secção de Questões e Estatísticas -->
    <div class="flex flex-col gap-6">
        <?php foreach ($questoes as $index => $q): ?>
            <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                <h2 class="font-bold text-slate-900 text-base sm:text-lg">
                    <?php echo ($index + 1) . '. ' . htmlspecialchars($q['enunciado']); ?>
                </h2>

                <?php 
                $tipo = $q['tipo_resposta'] ?? 'multipla_escolha';

                if ($tipo === 'escala' || $tipo === 'multipla_escolha'):
                    // Busca alternativas e a contagem de votos em cada uma
                    $stmtAlt = $pdo->prepare("
                        SELECT a.*, 
                        (SELECT COUNT(*) FROM respostas r WHERE r.alternativa_id = a.id) as total_votos 
                        FROM alternativas a 
                        WHERE a.questao_id = ? 
                        ORDER BY a.ordem ASC
                    ");
                    $stmtAlt->execute([$q['id']]);
                    $alternativas = $stmtAlt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                    <div class="flex flex-col gap-3 pt-2">
                        <?php foreach ($alternativas as $alt): 
                            $votos = $alt['total_votos'];
                            $porcentagem = $totalParticipacoes > 0 ? round(($votos / $totalParticipacoes) * 100) : 0;
                        ?>
                            <div class="flex flex-col gap-1.5 rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                                <div class="flex justify-between items-center text-sm font-medium text-slate-700">
                                    <span><?php echo htmlspecialchars($alt['texto']); ?></span>
                                    <!-- Texto da percentagem com cor dinâmica -->
                                    <span class="font-bold" style="color: var(--primary);"><?php echo $votos; ?> votos (<?php echo $porcentagem; ?>%)</span>
                                </div>
                                <!-- Barra de progresso da estatística com cor dinâmica -->
                                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full rounded-full transition-all" style="width: <?php echo $porcentagem; ?>%; background-color: var(--primary);"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php elseif ($tipo === 'texto'): 
                    // Busca todas as respostas textuais dadas pelos utilizadores sem referenciar colunas inexistentes
                    $stmtResp = $pdo->prepare("SELECT r.resposta_texto FROM respostas r JOIN participacoes p ON r.participacao_id = p.id WHERE r.questao_id = ? AND r.resposta_texto IS NOT NULL AND r.resposta_texto != '' ORDER BY p.id DESC");
                    $stmtResp->execute([$q['id']]);
                    $respostasTexto = $stmtResp->fetchAll(PDO::FETCH_ASSOC);
                ?>
                    <div class="flex flex-col gap-3 pt-2">
                        <?php if (count($respostasTexto) > 0): ?>
                            <div class="flex flex-col gap-2 max-h-60 overflow-y-auto pr-2">
                                <?php foreach ($respostasTexto as $rt): ?>
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3.5 text-sm text-slate-700">
                                        <p><?php echo nl2br(htmlspecialchars($rt['resposta_texto'])); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-slate-400 italic">Nenhuma resposta textual registada ainda.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'partials/footer.php'; ?>