<?php
include '../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questionario_id = isset($_POST['questionario_id']) ? intval($_POST['questionario_id']) : 0;

    if ($questionario_id <= 0) {
        header("Location: index.php");
        exit;
    }

    try {
        // 1. Inicia uma transação para garantir que a participação e as respostas são gravadas juntas
        $pdo->beginTransaction();

        // 2. Cria o registo na tabela 'participacoes' (o "recibo" de que o cliente respondeu)
        $stmtPart = $pdo->prepare("INSERT INTO participacoes (questionario_id, data_resposta) VALUES (?, NOW())");
        $stmtPart->execute([$questionario_id]);
        $participacao_id = $pdo->lastInsertId();

        // 3. Percorre todos os campos enviados pelo formulário
        foreach ($_POST as $chave => $valor) {
            // Verifica se o campo começa com 'resposta_' (que criamos no formulário para cada questão)
            if (strpos($chave, 'resposta_') === 0) {
                $questao_id = intval(str_replace('resposta_', '', $chave));
                
                // Descobre se a resposta é uma alternativa (ID numérico) ou texto livre
                $alternativa_id = null;
                $resposta_texto = null;

                if (is_numeric($valor)) {
                    $alternativa_id = intval($valor);
                } else {
                    $resposta_texto = trim($valor);
                }

                // 4. Insere o detalhe na tabela 'respostas'
                $stmtResp = $pdo->prepare("INSERT INTO respostas (participacao_id, questao_id, alternativa_id, resposta_texto) VALUES (?, ?, ?, ?)");
                $stmtResp->execute([$participacao_id, $questao_id, $alternativa_id, $resposta_texto]);
            }
        }

        // Confirma a transação no MySQL
        $pdo->commit();

        // Redireciona para uma página de sucesso
        header("Location: ../views/sucesso.php");
        exit;

    } catch (Exception $e) {
        // Se houver erro, desfaz qualquer alteração no banco
        $pdo->rollBack();
        echo "Erro ao guardar as respostas: " . $e->getMessage();
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>