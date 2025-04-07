<?php

class ChessModel extends Model {

    // Récupère la dernière position FEN d'une partie donnée
    public function getLastFEN($id) {
        $stmt = $this->db->prepare("SELECT fen FROM chess_positions WHERE game_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$id]);

        return $stmt->fetchColumn(); // Récupère directement la dernière position FEN
    }

    // Récupère toutes les positions FEN d'une partie donnée
    public function getFENs($id) {
        $stmt = $this->db->prepare("SELECT fen FROM chess_positions WHERE game_id = ? ORDER BY created_at ASC");
        $stmt->execute([$id]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Récupère toutes les positions FEN sous forme de tableau
    }

    // Récupère une position FEN spécifique par l'id de sa game ID
    public function getFEN($id) {
        $stmt = $this->db->prepare("SELECT fen FROM chess_positions WHERE game_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$id]);

        return $stmt->fetchColumn();
    }

    // Récupère une position FEN spécifique par son ID et par l'id de sa game ID
    public function getFENById($id, $id_fen) {
        $stmt = $this->db->prepare("SELECT fen FROM chess_positions WHERE game_id = ? AND id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$id,$id_fen]);

        return $stmt->fetchColumn();
    }

    // Récupère l'identifiant du gagnant d'une partie
    public function getWinner($id) {
        $stmt = $this->db->prepare("SELECT winner_id FROM chess_games WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetchColumn(); // Retourne l'identifiant du gagnant
    }

    // Récupère l'identifiant du perdant d'une partie
    public function getLooser($id) {
        $stmt = $this->db->prepare("SELECT loser_id FROM chess_games WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetchColumn(); // Retourne l'identifiant du perdant
    }

    // Crée une nouvelle partie d'échecs et insère une position de départ FEN
    public function createGame($white_id, $black_id, $FEN) {
        $this->db->beginTransaction();
        try {
            // Insérer une nouvelle partie
            $stmt = $this->db->prepare("INSERT INTO chess_games (white_id, black_id) VALUES (?, ?)");
            $stmt->execute([$white_id, $black_id]);

            // Obtenir l'ID de la partie nouvellement créée
            $game_id = $this->db->lastInsertId();

            // Insérer la position FEN de départ
            $stmt = $this->db->prepare("INSERT INTO chess_positions (game_id, fen) VALUES (?, ?)");
            $stmt->execute([$game_id, $FEN]);

            $this->db->commit();
            return $game_id; // Retourne l'ID de la partie créée
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Erreur lors de la création de la partie : " . $e->getMessage());
        }
    }

    // Met à jour la partie avec une nouvelle position FEN
    public function updateGame($id, $FEN) {
        // Ajouter une nouvelle position à la table des positions
        $stmt = $this->db->prepare("INSERT INTO chess_positions (game_id, fen) VALUES (?, ?)");
        $stmt->execute([$id, $FEN]);
    }
}
