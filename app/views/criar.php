<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: login.php');
    exit;
}

include '../../config/conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $publico_alvo = trim($_POST['publico_alvo'] ?? '');
    
    $enunciados = $_POST['enunciados'] ?? [];
    $tipos_resposta = $_POST['tipos_resposta'] ?? [];
    $alternativas_lista = $_POST['alternativas'] ?? [];

    if (!empty($titulo)) {
        try {
            $pdo->beginTransaction();

            // 1. Insere o questionário
            $stmtQ = $pdo->prepare("INSERT INTO questionarios (titulo, descricao, publico_alvo, status) VALUES (?, ?, ?, 'publicado')");
            $stmtQ->execute([$titulo, $descricao, $publico_alvo]);
            $questionario_id = $pdo->lastInsertId();

            // 2. Insere cada pergunta e suas respectivas alternativas
            foreach ($enunciados as $index => $enunciado) {
                $enunciadoTrim = trim($enunciado);
                if (!empty($enunciadoTrim)) {
                    $tipo = $tipos_resposta[$index] ?? 'texto';
                    $ordemQuestao = $index + 1;

                    $stmtP = $pdo->prepare("INSERT INTO questoes (questionario_id, enunciado, tipo_resposta, ordem) VALUES (?, ?, ?, ?)");
                    $stmtP->execute([$questionario_id, $enunciadoTrim, $tipo, $ordemQuestao]);
                    $questao_id = $pdo->lastInsertId();

                    // Insere alternativas se for múltipla escolha
                    if ($tipo === 'multipla_escolha' && !empty($alternativas_lista[$index])) {
                        $linhas = explode("\n", trim($alternativas_lista[$index]));
                        $ordemAlt = 1;
                        foreach ($linhas as $textoAlt) {
                            $textoAlt = trim($textoAlt);
                            if (!empty($textoAlt)) {
                                $stmtAlt = $pdo->prepare("INSERT INTO alternativas (questao_id, texto, ordem) VALUES (?, ?, ?)");
                                $stmtAlt->execute([$questao_id, $textoAlt, $ordemAlt]);
                                $ordemAlt++;
                            }
                        }
                    }
                }
            }

            $pdo->commit();
            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $erro = "Erro ao salvar no banco: " . $e->getMessage();
        }
    } else {
        $erro = "O campo Título é obrigatório.";
    }
}

include 'partials/header.php';
?>

<main class="mx-auto flex w-full max-w-2xl flex-col gap-6 px-4 py-8 text-left items-start">
    <h1 class="text-2xl font-bold text-white tracking-wide">Criar Novo Questionário</h1>

    <?php if (!empty($erro)): ?>
        <div class="w-full bg-[#2a0808] border border-red-900/50 text-red-400 p-4 rounded-xl text-xs font-medium">
            <?php echo htmlspecialchars($erro); ?>
        </div>
    <?php endif; ?>

    <form action="criar.php" method="POST" class="flex flex-col gap-6 w-full bg-neutral-900 p-6 rounded-2xl border border-neutral-800 shadow-sm">
        
        <!-- Seção: Dados do Questionário -->
        <div class="flex flex-col gap-4">
            <h2 class="text-xs font-extrabold text-orange-500 uppercase tracking-widest">1. Informações Gerais</h2>
            
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-neutral-200">Título do Questionário</label>
                <input type="text" name="titulo" required placeholder="Ex: Pesquisa de Satisfação" class="border border-neutral-700 bg-neutral-800 text-white placeholder:text-neutral-500 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-neutral-200">Descrição</label>
                <textarea name="descricao" rows="2" placeholder="Ex: Ajude-nos a melhorar nosso atendimento." class="border border-neutral-700 bg-neutral-800 text-white placeholder:text-neutral-500 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500"></textarea>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-neutral-200">Público-alvo</label>
                <input type="text" name="publico_alvo" placeholder="Ex: Alunos do SENAC" class="border border-neutral-700 bg-neutral-800 text-white placeholder:text-neutral-500 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500">
            </div>
        </div>

        <hr class="border-neutral-800">

        <!-- Seção: Pergunta(s) -->
        <div class="flex flex-col gap-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xs font-extrabold text-orange-500 uppercase tracking-widest">2. Perguntas do Questionário</h2>
                <button type="button" id="btn-add-pergunta" style="border-color: var(--primary); color: var(--primary);" class="px-3 py-1.5 bg-neutral-800 hover:bg-neutral-700 font-bold text-xs rounded-xl transition-colors border shadow-sm">
                    + Adicionar Pergunta
                </button>
            </div>

            <!-- Container onde as perguntas serão inseridas -->
            <div id="container-perguntas" class="flex flex-col gap-6"></div>
        </div>

        <div class="flex justify-end gap-3 mt-4">
            <a href="index.php" class="px-4 py-2 bg-neutral-800 hover:bg-neutral-700 text-neutral-300 font-semibold text-xs rounded-xl transition-colors border border-neutral-700 flex items-center">Cancelar</a>
            <button type="submit" style="background-color: var(--primary);" class="px-4 py-2 !text-white font-bold text-xs rounded-xl transition-opacity hover:opacity-90 shadow-sm">Salvar Questionário</button>
        </div>
    </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container-perguntas');
    const btnAdd = document.getElementById('btn-add-pergunta');

    function criarCardPergunta() {
        const div = document.createElement('div');
        div.className = 'bloco-pergunta flex flex-col gap-4 p-4 rounded-xl border border-neutral-800 bg-neutral-950/60 relative';

        div.innerHTML = `
            <div class="flex justify-between items-center">
                <span class="titulo-pergunta text-xs font-bold text-orange-400 tracking-wide">Pergunta #1</span>
                <button type="button" class="btn-remover px-2.5 py-1 bg-neutral-800 hover:bg-neutral-700 text-red-400 font-bold text-xs rounded-xl transition-colors border border-red-900/50 shadow-sm">Remover</button>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-neutral-200">Enunciado da Pergunta</label>
                <input type="text" name="enunciados[]" placeholder="Ex: Como você avalia nosso atendimento?" class="border border-neutral-700 bg-neutral-800 text-white placeholder:text-neutral-500 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-neutral-200">Tipo de Resposta</label>
                <select name="tipos_resposta[]" class="select-tipo border border-neutral-700 bg-neutral-800 text-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500">
                    <option value="escala">Escala (Ex: 1 a 10)</option>
                    <option value="multipla_escolha">Múltipla Escolha</option>
                    <option value="texto">Texto Livre</option>
                </select>
            </div>

            <div class="bloco-alternativas flex flex-col gap-1 hidden">
                <label class="text-xs font-bold text-neutral-200">Alternativas (Uma por linha)</label>
                <textarea name="alternativas[]" rows="3" placeholder="Ruim&#10;Regular&#10;Bom&#10;Excelente" class="input-alternativas border border-neutral-700 bg-neutral-800 text-white placeholder:text-neutral-500 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500"></textarea>
            </div>

            <div class="flex flex-col gap-2 mt-1 p-3.5 rounded-xl border border-neutral-800 bg-neutral-900/90 shadow-inner">
                <span class="text-[10px] font-extrabold text-neutral-400 uppercase tracking-widest">Pré-visualização para o respondente:</span>
                
                <div class="preview-escala flex flex-wrap gap-1.5 pt-1">
                    ${Array.from({length: 10}, (_, i) => `<div class="flex size-7 items-center justify-center rounded-lg border border-neutral-700 bg-neutral-800 text-xs font-bold text-orange-400">${i + 1}</div>`).join('')}
                </div>

                <div class="preview-multipla flex flex-col gap-2 pt-1 hidden"></div>

                <div class="preview-texto pt-1 hidden">
                    <div class="w-full h-16 border border-neutral-700 bg-neutral-800/80 rounded-xl p-2.5 text-xs text-neutral-400 italic">
                        O cliente vai digitar a resposta livremente aqui...
                    </div>
                </div>
            </div>
        `;

        container.appendChild(div);
        vincularEventosCard(div);
        reorganizarIndices();
    }

    function vincularEventosCard(card) {
        const selectTipo = card.querySelector('.select-tipo');
        const blocoAlt = card.querySelector('.bloco-alternativas');
        const inputAlt = card.querySelector('.input-alternativas');
        
        const previewEscala = card.querySelector('.preview-escala');
        const previewMultipla = card.querySelector('.preview-multipla');
        const previewTexto = card.querySelector('.preview-texto');
        const btnRemover = card.querySelector('.btn-remover');

        function atualizarCard() {
            const valor = selectTipo.value;
            blocoAlt.classList.add('hidden');
            previewEscala.classList.add('hidden');
            previewMultipla.classList.add('hidden');
            previewTexto.classList.add('hidden');

            if (valor === 'escala') {
                previewEscala.classList.remove('hidden');
            } else if (valor === 'multipla_escolha') {
                blocoAlt.classList.remove('hidden');
                previewMultipla.classList.remove('hidden');
                renderizarMultipla();
            } else if (valor === 'texto') {
                previewTexto.classList.remove('hidden');
            }
        }

        function renderizarMultipla() {
            const texto = inputAlt.value.trim();
            const linhas = texto ? texto.split('\n') : ['Exemplo de alternativa 1', 'Exemplo de alternativa 2'];
            previewMultipla.innerHTML = '';

            linhas.forEach(linha => {
                const altTexto = linha.trim() !== '' ? linha.trim() : '...';
                const item = document.createElement('div');
                item.className = 'flex items-center gap-2.5 text-xs text-neutral-200 bg-neutral-800/80 border border-neutral-700/60 p-2.5 rounded-lg';
                item.innerHTML = `<div class="size-3.5 rounded-full border border-orange-500 shrink-0"></div><span>${escapeHtml(altTexto)}</span>`;
                previewMultipla.appendChild(item);
            });
        }

        selectTipo.addEventListener('change', atualizarCard);
        inputAlt.addEventListener('input', renderizarMultipla);

        btnRemover.addEventListener('click', () => {
            if (container.querySelectorAll('.bloco-pergunta').length > 1) {
                card.remove();
                reorganizarIndices();
            } else {
                alert("O questionário precisa ter pelo menos uma pergunta.");
            }
        });

        atualizarCard();
    }

    function reorganizarIndices() {
        const blocos = container.querySelectorAll('.bloco-pergunta');
        blocos.forEach((bloco, idx) => {
            bloco.querySelector('.titulo-pergunta').textContent = `Pergunta #${idx + 1}`;
        });
    }

    function escapeHtml(str) {
        return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
    }

    // Adiciona a primeira pergunta por padrão ao abrir a página
    criarCardPergunta();

    // Adiciona nova pergunta ao clicar no botão
    btnAdd.addEventListener('click', () => {
        criarCardPergunta();
    });
});
</script>

<?php include 'partials/footer.php'; ?>