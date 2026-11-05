<?php
/**
 * Equivalente em PHP ao CVA Button do v0
 * 
 * @param string $texto Conteúdo do botão (texto ou ícones HTML)
 * @param array $options Opções: variant, size, tag ('button' ou 'a'), href, class, type, attrs
 */
function renderButton($texto, $options = []) {
    $tag = $options['tag'] ?? 'button'; // 'button' ou 'a'
    $variant = $options['variant'] ?? 'default';
    $size = $options['size'] ?? 'default';
    $href = $options['href'] ?? '#';
    $extraClass = $options['class'] ?? '';
    $type = $options['type'] ?? 'submit';
    $attrs = $options['attrs'] ?? '';

    // Classes base idênticas às geradas pelo CVA no v0
    $baseClasses = "inline-flex shrink-0 items-center justify-center rounded-xl font-medium transition-all outline-none select-none focus-visible:ring-3 focus-visible:ring-blue-900/40 disabled:pointer-events-none disabled:opacity-50 cursor-pointer gap-2";

    // Variantes visuais
    $variants = [
        'default'     => 'text-white shadow-sm hover:opacity-90',
        'outline'     => 'border border-slate-200 bg-white hover:bg-slate-50 text-slate-900 shadow-sm',
        'secondary'   => 'bg-slate-100 text-slate-900 hover:bg-slate-200',
        'ghost'       => 'hover:bg-slate-100 hover:text-slate-900',
        'destructive' => 'bg-red-50 text-red-700 hover:bg-red-100',
        'link'        => 'text-blue-900 underline-offset-4 hover:underline p-0 h-auto'
    ];

    // Tamanhos
    $sizes = [
        'default' => 'h-11 px-5 text-base',
        'xs'      => 'h-7 px-2 text-xs rounded-lg',
        'sm'      => 'h-9 px-3 text-sm rounded-lg',
        'lg'      => 'h-12 px-6 text-base',
        'icon'    => 'size-9 p-0'
    ];

    $variantClass = $variants[$variant] ?? $variants['default'];
    $sizeClass = ($variant === 'link') ? '' : ($sizes[$size] ?? $sizes['default']);

    // Se for o botão principal, aplica a cor dinâmica da paleta ativa (Variável CSS --primary)
    $bgStyle = ($variant === 'default') ? 'style="background-color: var(--primary);"' : '';

    $classes = trim("$baseClasses $variantClass $sizeClass $extraClass");

    if ($tag === 'a') {
        return '<a href="' . htmlspecialchars($href) . '" class="' . $classes . '" ' . $attrs . '>' . $texto . '</a>';
    } else {
        return '<button type="' . $type . '" class="' . $classes . '" ' . $bgStyle . ' ' . $attrs . '>' . $texto . '</button>';
    }
}
?>