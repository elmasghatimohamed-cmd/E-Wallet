<?php

namespace Src\Repositories;

use PDO;
use PDOException;
use PDOStatement;
use Exception;
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
    public function createUser(User $user): int
    {
        $query = 'INSERT INTO users (name, email, password, created_at) 
                  VALUES (:name, :email, :password, NOW())';
        $stmt = $this->db->prepare($query);


        try {
            $stmt->execute([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword()
            ]);

            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création de l'utilisateur : " . $e->getMessage());
        }
    }

    /**
     * Summary of updateUser
     * @param User $user
     * @return void
     */
    public function updateUser(User $user): void
    {
        $fields = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail()
        ];

        $query = 'UPDATE users SET name = :name, email = :email';

        if ($user->getPassword() !== null) {
            $query .= ', password = :password';
            $fields['password'] = $user->getPassword();
        }

        $query .= ', updated_at = NOW() WHERE id = :id';

        $stmt = $this->db->prepare($query);

        try {
            $stmt->execute($fields);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage());
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
            (int) $result['id'],
            $result['name'],
            $result['email'],
            $result['password'],
            $result['created_at'],
            $result['updated_at']
        );

    }

    /**
     * Summary of getUserByEmail 
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        $query = 'SELECT * FROM users WHERE email = :email LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->execute(['email' => $email]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return new User(
            (int) $result['id'],
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
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
        }
    }
}
