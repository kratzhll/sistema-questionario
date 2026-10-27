<?php
// Identifica a página atual para destacar no menu
$pagina_atual = basename($_SERVER['PHP_SELF']);
$is_palette_b = isset($_COOKIE['paleta']) && $_COOKIE['paleta'] === 'b';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opina - Sistema de Questionários</title>
    <!-- Tailwind CSS v3 via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: <?php echo $is_palette_b ? '#2e6b4e' : '#1f4e8c'; ?>;
            --primary-hover: <?php echo $is_palette_b ? '#23543d' : '#183d6f'; ?>;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 flex flex-col font-sans antialiased selection:bg-blue-900 selection:text-white">

    <!-- Header Fixo perfeitamente alinhado -->
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur shadow-sm">
        <div class="mx-auto flex max-w-3xl flex-wrap items-center justify-between gap-3 px-4 py-3">
            
            <!-- Logotipo -->
            <a href="index.php" class="flex items-center gap-2 rounded-md text-lg font-bold text-slate-900">
                <span class="flex size-9 items-center justify-center rounded-lg text-white shadow-sm" style="background-color: var(--primary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" class="w-5 h-5 shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </span>
                Opina
            </a>

            <!-- Navegação com destaque dinâmico para a página ativa -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                <nav aria-label="Principal">
                    <ul class="flex items-center gap-1">
                        <li>
                            <a href="index.php" class="rounded-lg px-3.5 py-2 text-sm font-bold transition-colors <?php echo ($pagina_atual === 'index.php' || $pagina_atual === 'responder.php') ? 'bg-slate-100 text-slate-900 shadow-sm' : 'text-slate-600 hover:bg-slate-100/60 hover:text-slate-900'; ?>">
                                Questionários
                            </a>
                        </li>
                        <li>
                            <a href="admin.php" class="rounded-lg px-3.5 py-2 text-sm font-bold transition-colors <?php echo ($pagina_atual === 'admin.php' || $pagina_atual === 'relatorio.php') ? 'bg-slate-100 text-slate-900 shadow-sm' : 'text-slate-600 hover:bg-slate-100/60 hover:text-slate-900'; ?>">
                                Administração
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- Formulário de Seleção de Paleta A/B -->
                <form action="../../app/controllers/mudar_paleta.php" method="POST" class="flex items-center gap-1 rounded-lg border border-slate-200 p-1 bg-slate-50">
                    <button type="submit" name="paleta" value="a" class="flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-bold transition-colors <?php echo !$is_palette_b ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:bg-slate-200/60'; ?>">
                        <span class="size-3 rounded-full bg-[#1f4e8c]"></span>
                        Paleta A
                    </button>
                    <button type="submit" name="paleta" value="b" class="flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-bold transition-colors <?php echo $is_palette_b ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:bg-slate-200/60'; ?>">
                        <span class="size-3 rounded-full bg-[#2e6b4e]"></span>
                        Paleta B
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div id="conteudo" class="flex flex-1 flex-col">