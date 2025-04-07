<div class="bg-soviet-lightgray py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-6 uppercase tracking-wider">Chess-SPI</h1>
        <p class="text-xl mb-8 max-w-3xl mx-auto">Les échecs au service de la science.</p>
        
        <div class="flex justify-center space-x-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/dashboard" class="soviet-button text-lg px-8 py-3">Accéder au Tableau de Bord</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" class="soviet-button text-lg px-8 py-3">Connexion</a>
                <a href="<?= BASE_URL ?>/register" class="bg-soviet-gray text-white text-lg px-8 py-3 hover:bg-black">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</div>

