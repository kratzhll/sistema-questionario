<?php
/**
 * Renderiza um alerta de erro ou sucesso padrão idêntico ao v0
 */
function renderAlert($tipo, $mensagem, $erros = []) {
    if (empty($mensagem)) return '';

    if ($tipo === 'sucesso') {
        return '
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800 flex items-center gap-2 shadow-sm mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="font-bold">' . htmlspecialchars($mensagem) . '</span>
        </div>';
    } else {
        $listaErros = '';
        if (!empty($erros)) {
            $listaErros .= '<ul class="mt-1 list-disc pl-5 text-xs space-y-1">';
            foreach ($erros as $erro) {
                $listaErros .= '<li>' . htmlspecialchars($erro) . '</li>';
            }
            $listaErros .= '</ul>';
        }

        return '
        <div role="alert" class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700 shadow-sm mb-4">
            <p class="flex items-center gap-2 font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                ' . htmlspecialchars($mensagem) . '
            </p>
            ' . $listaErros . '
        </div>';
    }
}
?>