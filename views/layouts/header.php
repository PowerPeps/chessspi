<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPI - Système de Jeu d'Échecs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        spi: {
                            lightgreen: '#CFDBD5',
                            palegreen: '#E8EDDF',
                            yellow: '#F5CB5C',
                            orange: '#F58536',
                            red: '#C5221F',
                            darkred: '#8B0000',
                            darkgray: '#242423',
                            mediumgray: '#333533',
                        }
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                        serif: ['Andada Pro', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Andada+Pro:ital,wght@0,400..840;1,400..840&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        body {
            font-family: Montserrat, sans-serif;
            background-color: #CFDBD5;
        }
        .spi-button {
            background-color: #F5CB5C;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            text-transform: uppercase;
            font-weight: bold;
            transition: background-color 0.3s;
            border-radius: 5px;
        }
        .spi-button:hover {
            background-color: #F58536;
        }
        .spi-navbar {
            background-color: #242423;
            color: #E8EDDF;
            border-bottom: 2px solid #333533;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .spi-nav-link {
            color: #E8EDDF;
            text-transform: uppercase;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        .spi-nav-link:hover {
            color: #F5CB5C;
        }
        .spi-title {
            font-family: 'Andada Pro', serif;
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-spi-lightgreen min-h-screen">
<nav class="spi-navbar py-3">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a href="<?= BASE_URL ?>/" class="spi-title flex items-center space-x-2">
            <span>&#9812;</span> <!-- Unicode pour un roi d'échecs -->
            <span>SPI - Jeu d'Échecs</span>
        </a>
        <div class="flex items-center space-x-6">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/dashboard" class="spi-nav-link">Tableau de Bord</a>
                <a href="<?= BASE_URL ?>/games" class="spi-nav-link">Parties</a>
                <a href="<?= BASE_URL ?>/leaderboard" class="spi-nav-link">Classement</a>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <a href="<?= BASE_URL ?>/users" class="spi-nav-link">Utilisateurs</a>
                    <a href="<?= BASE_URL ?>/settings" class="spi-nav-link">Paramètres</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/logout" class="spi-button">Déconnexion</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" class="spi-nav-link">Connexion</a>
                <a href="<?= BASE_URL ?>/register" class="spi-button">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container mx-auto px-4 py-8">
    <!-- Contenu principal -->