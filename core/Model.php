<?php
class Model {
    protected $db;
    protected $table;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function findAll() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data) {
        $setClause = '';
        foreach (array_keys($data) as $key) {
            $setClause .= "$key = :$key, ";
        }
        $setClause = rtrim($setClause, ', ');
        
        $stmt = $this->db->prepare("UPDATE {$this->table} SET $setClause WHERE id = :id");
        $stmt->bindParam(':id', $id);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}

