<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?= APP_PATH ?>/public/chessboard/js/chessboard-1.0.0.js"></script>
<link rel="stylesheet" href="<?= APP_PATH ?>/public/chessboard/css/chessboard-1.0.0.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/chess.js/0.10.2/chess.min.js"></script>
<div>
    <div id="myBoard" style="width: 400px"></div>
</div>


<script>
    // Configuration et initialisation
    var board = null;
    var game = new Chess();
    var whiteSquareGrey = '#a9a9a9';
    var blackSquareGrey = '#696969';

    // Récupération des données du jeu depuis le serveur
    var gameData = <?= json_encode($data['$game'])?>;
    console.log("Données du serveur :", gameData);
    var initialPosition = gameData ? gameData : 'start';
    console.log("Position initiale :", initialPosition);

    // Fonctions d'aide pour l'interface utilisateur
    function removeGreySquares() {
        $('#myBoard .square-55d63').css('background', '');
    }

    function greySquare(square) {
        var $square = $('#myBoard .square-' + square);

        var background = whiteSquareGrey;
        if ($square.hasClass('black-3c85d')) {
            background = blackSquareGrey;
        }

        $square.css('background', background);
    }

    // Fonction pour mettre à jour la position sur le serveur
    function updatePositionOnServer(source, target, fen, moveSan) {
        // Créer un objet XMLHttpRequest directement au lieu d'utiliser jQuery
        var xhr = new XMLHttpRequest();
        var url = window.location.origin + '<?= APP_PATH ?>/game/<?= $data['$gameId'] ?>/update-position';

        console.log("Envoi de la mise à jour au serveur avec l'URL:", url);
        console.log("Origin:", window.location.origin);
        console.log("APP_PATH:", '<?= APP_PATH ?>');
        console.log("Final URL utilisée:", url);


        // Ouvrir la connexion avec la méthode POST
        xhr.open('POST', url, true);

        // Définir l'en-tête Content-Type
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Gérer les changements d'état
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    console.log("Mise à jour réussie:", xhr.responseText);
                } else {
                    console.error("Erreur lors de la mise à jour:", xhr.status, xhr.responseText);
                }
            }
        };

        // Préparer les données
        var data = 'fen=' + encodeURIComponent(fen);
        if (source && target && moveSan) {
            data += '&from=' + encodeURIComponent(source);
            data += '&to=' + encodeURIComponent(target);
            data += '&move=' + encodeURIComponent(moveSan);
        }
        console.log(xhr.open);
        // Envoyer la requête
        xhr.send(data);
    }

    // Gestionnaires d'événements du jeu
    function onDragStart(source, piece) {
        // Ne pas prendre de pièces si le jeu est terminé
        if (game.game_over()) return false;

        // Ou si ce n'est pas le tour de ce côté
        if ((game.turn() === 'w' && piece.search(/^b/) !== -1) ||
            (game.turn() === 'b' && piece.search(/^w/) !== -1)) {
            return false;
        }

        return true;
    }

    function onDrop(source, target) {
        removeGreySquares();

        // Vérifier si le mouvement est légal
        var move = game.move({
            from: source,
            to: target,
            promotion: 'q' // NOTE: toujours promouvoir en reine pour simplifier l'exemple
        });

        // Mouvement illégal
        if (move === null) return 'snapback';

        console.log("Mouvement effectué:", move);
        console.log("Nouveau FEN:", game.fen());

        // Mettre à jour le serveur avec les détails du mouvement
        // Utiliser notre nouvelle fonction qui contourne jQuery.ajax
        setTimeout(function() {
            updatePositionOnServer(source, target, game.fen(), move.san);
        }, 100);

        return true;
    }

    function onMouseoverSquare(square, piece) {
        // Obtenir la liste des mouvements possibles pour cette case
        var moves = game.moves({
            square: square,
            verbose: true
        });

        // Sortir s'il n'y a pas de mouvements disponibles pour cette case
        if (moves.length === 0) return;

        // Mettre en surbrillance la case survolée
        greySquare(square);

        // Mettre en surbrillance les cases possibles pour cette pièce
        for (var i = 0; i < moves.length; i++) {
            greySquare(moves[i].to);
        }
    }

    function onMouseoutSquare(square, piece) {
        removeGreySquares();
    }

    function onSnapEnd() {
        // Mettre à jour la position du plateau
        board.position(game.fen());
    }

    function updateStatus() {
        var xhr = new XMLHttpRequest();
        var url = window.location.origin + '<?= APP_PATH ?>/game/<?= $data['$gameId'] ?>/get-status';

        console.log("Récupération du statut depuis:", url);

        xhr.open('GET', url, true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    console.log("Statut reçu du serveur:", xhr.responseText);
                } else {
                    console.error("Erreur lors de la récupération du statut:", xhr.status, xhr.responseText);
                }
            }
        };

        xhr.send();
    }

    // Configuration du plateau
    var config = {
        pieceTheme: '<?= APP_PATH ?>/public/chessboard/img/chesspieces/wikipedia/{piece}.png',
        draggable: true,
        position: initialPosition,
        onDragStart: onDragStart,
        onDrop: onDrop,
        onMouseoutSquare: onMouseoutSquare,
        onMouseoverSquare: onMouseoverSquare,
        onSnapEnd: onSnapEnd
    };

    // Initialiser le plateau et démarrer le jeu
    $(document).ready(function() {
        board = Chessboard('myBoard', config);
        updateStatus();

        console.log("Plateau d'échecs initialisé avec la configuration:", config);
        console.log("URL actuelle:", window.location.href);
        console.log("URL de base:", window.location.origin);
    });


    (function() {
        let originalFetch = window.fetch;
        window.fetch = function() {
            console.warn("🚨 fetch intercepté:", arguments[0]);
            return originalFetch.apply(this, arguments);
        };

        let originalXHR = XMLHttpRequest.prototype.open;
        XMLHttpRequest.prototype.open = function(method, url) {
            console.warn("🚨 XHR intercepté:", method, url);
            return originalXHR.apply(this, arguments);
        };

        let originalSubmit = HTMLFormElement.prototype.submit;
        HTMLFormElement.prototype.submit = function() {
            console.warn("🚨 Form submit intercepté:", this.action);
            return originalSubmit.apply(this, arguments);
        };

        let originalLocation = Object.getOwnPropertyDescriptor(window, "location");
        Object.defineProperty(window, "location", {
            set: function(value) {
                console.warn("🚨 Redirection interceptée vers:", value);
                originalLocation.set.call(this, value);
            },
            get: function() {
                return originalLocation.get.call(this);
            }
        });
    })();


</script>