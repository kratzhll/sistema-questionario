<?php include 'partials/header.php'; ?>

<main class="mx-auto flex w-full max-w-md flex-col items-center text-center gap-6 px-4 py-20">
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
    <!-- Ícone temático de Halloween (Abóbora / Sucesso) -->
    <div class="flex items-center justify-center size-20 rounded-full bg-orange-500/10 border-2 border-orange-500 text-orange-500 mb-6 shadow-lg shadow-orange-500/20 animate-bounce">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>

    <!-- Título -->
    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Respostas enviadas com sucesso!</h1>
    
    <!-- Subtítulo -->
    <p class="text-slate-400 max-w-md mb-8">
        Muito obrigado pela sua participação. A sua opinião é fundamental para melhorarmos os nossos doces e serviços!
    </p>

    <!-- Botão com estilo Halloween (Laranja) -->
    <a href="index.php" class="inline-flex items-center justify-center px-6 py-3 rounded-xl font-bold !text-white text-white bg-orange-600 hover:bg-orange-500 transition-colors shadow-lg shadow-orange-600/30">
    <span class="text-white !text-white">Voltar à página inicial</span>
    </a>
</div>
</main>

<?php include 'partials/footer.php'; ?>