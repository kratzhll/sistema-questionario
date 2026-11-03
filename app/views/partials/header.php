<?php
// Identifica a página atual para destacar no menu
$pagina_atual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spooky Sweets - Questionários</title>
    
    <!-- Importação da fonte Geist -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v3 via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --primary: #f97316;
            --primary-hover: #ea580c;
        }
        body {
            font-family: 'Geist', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #0c0a09;
            color: #ffffff !important;
        }
        .font-spooky {
            font-family: 'Creepster', cursive, sans-serif;
            letter-spacing: 0.05em;
        }

        /* TEXTOS SECUNDÁRIOS EM CINZA CLARO */
        p:not(h1 + p), span:not(.rounded-full):not(h1 span):not(.text-white), label:not(.card-title), 
        .text-slate-600, .text-slate-900, 
        .text-gray-600, .text-gray-900,
        .text-slate-400, .text-slate-500,
        .text-sm:not(a *):not(button *), .text-xs {
            color: #9ca3af !important;
        }

        /* TÍTULOS PRINCIPAIS E DESTAQUES EM BRANCO */
        h1, h2, h3:not(.card-title), .text-3xl, .text-2xl, h2[class*="text-"] {
            color: #ffffff !important;
        }

        /* TÍTULOS DOS CARTÕES E PERGUNTAS EM LARANJA */
        .card-title, .text-lg.font-bold, div[class*="font-bold"], h3[class*="text-"] {
            color: #f97316 !important;
        }

        .text-orange-500, .text-orange-400 {
            color: #f97316 !important;
        }

        /* LINKS DE RETORNO / "TODOS OS QUESTIONÁRIOS" EM LARANJA */
        a[href*="index.php"], a[href*="admin.php"], .text-blue-600, .text-blue-500, [class*="text-indigo"] {
            color: #f97316 !important;
        }
        a[href*="index.php"]:hover, a[href*="admin.php"]:hover {
            color: #ea580c !important;
        }

        /* CARTÕES E BLOCOS (INCLUINDO CAIXA DE RESPOSTAS ENVIADAS) */
        .bg-white, [class*="bg-white"], 
        div.bg-white, section.bg-white, article.bg-white,
        .bg-slate-100, .bg-gray-100, [class*="bg-slate-"], [class*="bg-gray-"] {
            background-color: #171717 !important;
            border: 1px solid #262626 !important;
            color: #ffffff !important;
        }
        
        .bg-white:hover, [class*="bg-white"]:hover {
            border-color: #404040 !important;
        }

        /* DESTAQUE DO NÚMERO DE RESPOSTAS ENVIADAS EM LARANJA */
        .bg-white span, [class*="bg-slate-"] span, [class*="bg-gray-"] span,
        div[class*="rounded"] span.text-2xl, div[class*="rounded"] span.font-bold {
            color: #f97316 !important;
        }

        /* ALTERNATIVAS NÃO SELECIONADAS */
        label:has(input[type="radio"]), div:has(> input[type="radio"]) {
            background-color: #121212 !important;
            border: 1px solid #262626 !important;
            color: #d1d5db !important;
        }

        /* CÍRCULOS DOS NÚMEROS PADRÃO */
        label:has(input[type="radio"]) span.rounded-full, 
        div:has(> input[type="radio"]) span.rounded-full,
        .rounded-full:not(.bg-blue-600):not(.bg-blue-500):not([style*="width"]) {
            background-color: initial !important;
            color: inherit !important;
        }

        /* ALTERNATIVA SELECIONADA EM LARANJA */
        label:has(input[type="radio"]:checked), 
        div:has(> input[type="radio"]:checked),
        .bg-white:has(input[type="radio"]:checked),
        [class*="bg-white"]:has(input[type="radio"]:checked) {
            background-color: #2a1408 !important;
            border-color: #f97316 !important;
            color: #ffffff !important;
        }
        
        /* ETIQUETA "PUBLICADO" E "OBRIGATÓRIA" EM LARANJA */
        .rounded-full.bg-slate-100, .rounded-full.bg-gray-100, [class*="Obrigató"],
        .bg-green-100, .bg-emerald-100, [class*="green"], [class*="emerald"] {
            color: #f97316 !important;
            border-color: #f97316 !important;
            background-color: #2a1408 !important;
        }

        /* BARRAS DE ESTATÍSTICA DO RELATÓRIO EM LARANJA */
        div.bg-slate-200, div.bg-gray-200, div[class*="w-full"][class*="rounded-full"][class*="bg-"] {
            background-color: #262626 !important;
        }
        div[style*="width"], span[style*="width"], .bg-blue-600, .bg-blue-500, .bg-indigo-600 {
            background-color: #f97316 !important;
        }

        /* BOTÕES DE NAVEGAÇÃO DO HEADER ORIGINAIS */
        header button:not([type="radio"]), header .btn, header a.btn, header a[class*="rounded"] {
            background-color: transparent !important;
            color: #d1d5db !important;
        }
        header a[class*="rounded"]:hover, header button:hover {
            background-color: #262626 !important;
            color: #ffffff !important;
        }

        /* BOTÕES DE AÇÃO GERAIS COM FORÇAGEM ABSOLUTA DE TEXTO E ÍCONES BRANCOS */
        body > div button:not([type="radio"]), .btn:not(header .btn), a.btn:not(header a.btn), 
        button[class*="bg-blue"]:not(header button), [class*="bg-indigo"]:not(header *), 
        main a[class*="rounded"]:not([class*="text-"]) {
            background-color: #f97316 !important;
            color: #ffffff !important;
            border: none !important;
        }
        
        body > div button:not([type="radio"]) *, .btn:not(header .btn) *, a.btn:not(header a.btn) *,
        a[href*="responder.php"], a[href*="responder.php"] *,
        a[href*="relatorio"], a[href*="relatorio"] * {
            color: #ffffff !important;
            fill: #ffffff !important;
            stroke: #ffffff !important;
        }

        body > div button:not([type="radio"]):hover, .btn:not(header .btn):hover, a.btn:not(header a.btn):hover,
        a[href*="relatorio"]:hover {
            background-color: #ea580c !important;
            color: #ffffff !important;
        }

        /* INPUTS E CAMPOS DE TEXTO */
        input[type="text"], textarea, select {
            background-color: #121212 !important;
            color: #ffffff !important;
            border-color: #262626 !important;
        }

        /* RODAPÉ (FOOTER) */
        footer, .footer, [class*="footer"], body > footer {
            background-color: #121212 !important;
            border-top: 1px solid #262626 !important;
            color: #9ca3af !important;
        }
    </style>
</head>
<body class="min-h-screen bg-[#0c0a09] text-[#f5f5f5] flex flex-col antialiased selection:bg-orange-600 selection:text-white">

    <!-- Header de Halloween com fundo escuro translúcido -->
    <header class="sticky top-0 z-50 border-b border-neutral-800 bg-[#121212]/95 backdrop-blur shadow-md">
        <div class="mx-auto flex max-w-4xl flex-wrap items-center justify-between gap-3 px-4 py-3">
            
            <!-- Logotipo idêntico ao modelo de referência -->
            <a href="index.php" class="flex items-center gap-2 text-2xl font-spooky text-orange-500 hover:text-orange-400 transition-colors">
                🎃 Spooky Sweets
            </a>

            <!-- Navegação com destaque dinâmico -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                <nav aria-label="Principal">
                    <ul class="flex items-center gap-1">
                        <li>
                            <a href="index.php" class="rounded-lg px-3.5 py-2 text-sm font-bold transition-colors <?php echo ($pagina_atual === 'index.php' || $pagina_atual === 'responder.php') ? 'bg-neutral-800 text-orange-400 shadow-sm' : 'text-neutral-400 hover:bg-neutral-800/60 hover:text-white'; ?>">
                                Questionários
                            </a>
                        </li>
                        <li>
                            <a href="admin.php" class="rounded-lg px-3.5 py-2 text-sm font-bold transition-colors <?php echo ($pagina_atual === 'admin.php' || $pagina_atual === 'relatorio.php') ? 'bg-neutral-800 text-orange-400 shadow-sm' : 'text-neutral-400 hover:bg-neutral-800/60 hover:text-white'; ?>">
                                Administração
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div id="conteudo" class="flex flex-1 flex-col">