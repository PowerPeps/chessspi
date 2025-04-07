<?php
class ChessController extends Controller {

    protected $chessModel;

    public function __construct() {
        $this->chessModel = new ChessModel();
    }

    public function index()
    {
        $this->view('chess/index');
    }

    public function newgame()
    {
        $white_id = $_SESSION['user_id'];
        $black_id = $_POST['opponent_id'] ?? 0;
        $FEN = isset($_POST['fen']) ? $_POST['fen'] : DEFAULT_FEN;
        header('Location: '. APP_PATH .'/game/' .$this->chessModel->createGame($white_id, $black_id, $FEN).'/');
    }

    public function getstatus($id) {
        echo json_encode(['id'=>(int)$id]);
    }

    public function game($id)
    {
        $this->view('chess/viewgame2', [
            '$fen' => $this->chessModel->getLastFEN($id),
            '$gameId' => $id
        ]);
    }

    public function getposition($id)
    {
        if(isset($_GET['fen_id'])) {
            echo json_encode(['FEN' => $this->chessModel->getFENById($id,$_GET['fen_id']), 'id_game' => $id, 'id_fen' => $_GET['fen_id']]);
        } else {
            echo json_encode(['FEN' => $this->chessModel->getFEN($id)]);
        }
    }


    public function updateposition($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification de la donnée FEN envoyée via le formulaire
            $fen = $_POST['fen'] ?? null;
            if ($fen) {
                try {
                    // Mise à jour de la position dans la base de données
                    if($this->legalFen($this->chessModel->getLastFEN($id), $fen)) {
                        $this->chessModel->updateGame($id, $fen);
                        echo json_encode(['error' => false]);
                        exit;
                    } else {
                        echo json_encode(['error' => true, 'errorType' => 'illegalMove']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['error' => $e->getMessage()]);
                    exit;
                }
            } else {
                echo json_encode(['error' => true, 'errorType' =>  'Aucune position FEN fournie']);
            }
        }
    }

    // -- CHESS LOGIC -- //

    private function legalFen($lastFen, $newFen)
    {
         //Vérifie si c'est la bonne couleur qui joue
         if (!$this->isCorrectTurn($lastFen, $newFen)) {
            return false;
         }

        // Vérifie si le déplacement est légal pour la pièce
        if (!$this->isLegalMove($lastFen, $newFen)) {
            return false;
        }

        // Vérifie que le roi n'est pas en échec après le coup
        if (!$this->isKingSafe($newFen)) {
            return false;
        }

        //TODO prise de piece.

        return true;
    }

    private function isLegalMove($lastFen, $newFen)
    {
        // Décomposer les positions FEN pour extraire le plateau
        $lastBoard = $this->getBoardFromFen($lastFen);
        $newBoard = $this->getBoardFromFen($newFen);

        // Identifier les différences entre les plateaux
        $move = $this->findMove($lastBoard, $newBoard);
        if (!$move) {
            return false; // Aucun mouvement détecté
        }

        [$piece, $startPosition, $endPosition] = $move;

        // Valider les règles spécifiques à chaque pièce
        if (!$this->isValidPieceMove($piece, $startPosition, $endPosition, $lastBoard)) {
            return false;
        }

        // Vérifier les obstacles potentiels sur le chemin
        if ($this->hasObstacles($piece, $startPosition, $endPosition, $lastBoard)) {
            return false;
        }

        // Autres validations (échec, échec et mat, promotion, etc.)
        // TODO : Implémenter ces vérifications avancées

        return true; // Si toutes les validations réussissent
    }

    // Décompose le plateau à partir de la notation FEN
    private function getBoardFromFen($fen)
    {
        $parts = explode(' ', $fen); // Diviser la FEN en sections
        $rows = explode('/', $parts[0]); // Obtenir les rangées du plateau
        $board = [];

        foreach ($rows as $row) {
            $line = [];
            foreach (str_split($row) as $char) {
                if (is_numeric($char)) {
                    $line = array_merge($line, array_fill(0, intval($char), null));
                } else {
                    $line[] = $char;
                }
            }
            $board[] = $line;
        }
        return $board;
    }

    // Trouve le mouvement effectué en comparant deux plateaux
    private function findMove($lastBoard, $newBoard)
    {
        $start = null;
        $end = null;
        $piece = null;

        for ($row = 0; $row < 8; $row++) {
            for ($col = 0; $col < 8; $col++) {
                if ($lastBoard[$row][$col] !== $newBoard[$row][$col]) {
                    if ($lastBoard[$row][$col] !== null) {
                        $start = [$row, $col];
                        $piece = $lastBoard[$row][$col];
                    }
                    if ($newBoard[$row][$col] !== null) {
                        $end = [$row, $col];
                    }
                }
            }
        }

        if ($start && $end) {
            return [$piece, $start, $end];
        }

        return null; // Pas de mouvement valide détecté
    }

    // Valide le mouvement d'une pièce spécifique
    private function isValidPieceMove($piece, $start, $end, $board)
    {
        // NOTE : Implémentez les règles spécifiques à chaque pièce ici.
        $dx = abs($end[0] - $start[0]);
        $dy = abs($end[1] - $start[1]);

        switch (strtolower($piece)) {
            case 'p': // Pion
                // Exemple : vérifier les mouvements avant (et diagonales pour les captures)
                return $this->isValidPawnMove($piece, $start, $end, $board);
            case 'r': // Tour
                return $dx === 0 || $dy === 0;
            case 'n': // Cavalier
                return ($dx === 2 && $dy === 1) || ($dx === 1 && $dy === 2);
            case 'b': // Fou
                return $dx === $dy;
            case 'q': // Reine
                return $dx === $dy || $dx === 0 || $dy === 0;
            case 'k': // Roi
                return $dx <= 1 && $dy <= 1;
        }

        return false;
    }

    // Vérifie les obstacles sur le chemin
    private function hasObstacles($piece, $start, $end, $board)
    {
        // Implémentation simplifiée pour vérifier les obstacles en ligne droite ou diagonale
        $dx = $end[0] - $start[0];
        $dy = $end[1] - $start[1];

        $stepX = $dx === 0 ? 0 : $dx / abs($dx);
        $stepY = $dy === 0 ? 0 : $dy / abs($dy);

        $x = $start[0] + $stepX;
        $y = $start[1] + $stepY;

        while ($x !== $end[0] || $y !== $end[1]) {
            if ($board[$x][$y] !== null) {
                return true; // Il y a un obstacle
            }
            $x += $stepX;
            $y += $stepY;
        }

        return false;
    }

    // Exemples de validation des pions
    private function isValidPawnMove($piece, $start, $end, $board)
    {
        $direction = ctype_upper($piece) ? -1 : 1;
        $dx = $end[0] - $start[0];
        $dy = abs($start[1] - $end[1]);

        // Avancer d'une case
        if ($dx === $direction && $dy === 0 && $board[$end[0]][$end[1]] === null) {
            return true;
        }

        // Capturer en diagonale
        if ($dx === $direction && $dy === 1 && $board[$end[0]][$end[1]] !== null) {
            return true;
        }

        // Avancer de deux cases depuis la position initiale
        $initialRow = ctype_upper($piece) ? 6 : 1;
        if ($start[0] === $initialRow && $dx === 2 * $direction && $dy === 0 && $board[$end[0]][$end[1]] === null) {
            return true;
        }

        return false;
    }

    private function isCorrectTurn($lastFen, $newFen) {
        $lastTurn = getTrait($lastFen);
        $newTurn = getTrait($newFen);
        return $lastTurn === $newTurn;
    }

    private function getTrait($fen) {
        return(explode(' ', $fen)[1]);
    }

    private function isKingSafe($fen)
    {
        // TODO : Implémenter une méthode pour vérifier si le roi n'est pas en "prise".
        // 1. Identifiez la position du roi (blanc ou noir).
        // 2. Vérifiez toutes les attaques possibles des pièces adverses.
        return true; // Temporaire, à compléter
    }


}
