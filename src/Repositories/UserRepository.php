<?php

namespace Src\Repositories;

use PDO;
use Src\Models\User;

class UserRepository
{
    private PDO $db;

    /**
     * Summary of __construct
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Summary of createUser
     * @param User $user
     * @return void
     */
    public function createUser(User $user): void
    {
        $query = 'INSERT INTO users (name, email, password, created_at) 
                  VALUES (:name, :email, :password, NOW())';
        $stmt = $this->db->prepare($query);

        $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);

        try {
            $stmt->execute([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $hashedPassword
            ]);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la création de l'utilisateur : " . $e->getMessage());
        }
    }

    /**
     * Summary of updateUser
     * @param User $user
     * @return void
     */
    public function updateUser(User $user): void
    {
        // Si le mot de passe est fourni, on le hash, sinon on ne change pas le password
        $fields = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail()
        ];

        $query = 'UPDATE users SET name = :name, email = :email';

        if ($user->getPassword()) {
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);
            $query .= ', password = :password';
            $fields['password'] = $hashedPassword;
        }

        $query .= ', updated_at = NOW() WHERE id = :id';

        $stmt = $this->db->prepare($query);

        try {
            $stmt->execute($fields);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage());
        }
    }

    /**
     * Summary of getUserById
     * @param int $id
     * @return ?User
     */
    public function getUserById(int $id): ?User
    {
        $query = 'SELECT * FROM users WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }

        return new User(
            $result['id'],
            $result['name'],
            $result['email'],
            $result['password'],
            $result['created_at'],
            $result['updated_at']
        );
    }

    /**
     * Summary of deleteUser
     * @param int $id
     * @return void
     */
    public function deleteUser(int $id): void
    {
        $query = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->db->prepare($query);

        try {
            $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
        }
    }
}
