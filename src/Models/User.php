<?php

namespace App\Models;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, first_name, last_name, position 
                FROM users 
                WHERE is_deleted = 0
                ORDER BY id DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            error_log("Error getting users: " . $e->getMessage());
            return [];
        }
    }

    public function create($data)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (first_name, last_name, position) 
                VALUES (:first_name, :last_name, :position)
            ");
            
            $params = [
                'first_name' => htmlspecialchars(strip_tags($data['first_name'])),
                'last_name' => htmlspecialchars(strip_tags($data['last_name'])),
                'position' => htmlspecialchars(strip_tags($data['position']))
            ];

            $result = $stmt->execute($params);
            
            if (!$result) {
                throw new \Exception("Failed to create user");
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET first_name = :first_name, 
                last_name = :last_name, 
                position = :position 
            WHERE id = :id AND is_deleted = 0
        ");
        
        return $stmt->execute([
            'id' => (int)$id,
            'first_name' => htmlspecialchars(strip_tags($data['first_name'])),
            'last_name' => htmlspecialchars(strip_tags($data['last_name'])),
            'position' => htmlspecialchars(strip_tags($data['position']))
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE users SET is_deleted = 1 WHERE id = :id");
        return $stmt->execute(['id' => (int)$id]);
    }
}
