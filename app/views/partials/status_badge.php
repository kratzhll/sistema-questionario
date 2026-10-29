<?php
/**
 * Renderiza o badge de status idêntico ao do v0 em PHP puro
 */
function renderStatusBadge($status) {
    // Define os estilos, ícones (SVG) e textos baseados no status
    switch ($status) {
        case 'publicado':
            $classes = 'border-blue-900/30 bg-blue-50 text-blue-900';
            $texto = 'Publicado';
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
            break;
            
        case 'encerrado':
            $classes = 'border-slate-300 bg-white text-slate-500';
            $texto = 'Encerrado';
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>';
            break;
            
        case 'rascunho':
        default:
            $classes = 'border-slate-300 bg-slate-100 text-slate-700';
            $texto = 'Rascunho';
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>';
            break;
    }

    $baseStyle = 'inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-xs font-bold shadow-sm';

    return '<span class="' . $baseStyle . ' ' . $classes . '">' . $svg . ' ' . $texto . '</span>';
}

/**
 * Renderiza o badge de inativo
 */
function renderInativoBadge($label = 'Inativo') {
    $baseStyle = 'inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-xs font-bold shadow-sm';
    $classes = 'border-red-200 bg-red-50 text-red-700';
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>';

    return '<span class="' . $baseStyle . ' ' . $classes . '">' . $svg . ' ' . $label . '</span>';
}
?>