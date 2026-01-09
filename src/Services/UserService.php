<?php

namespace Src\Services;

use Src\Repositories\UserRepository;
use Src\Models\User;
use Exception;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_secure' => isset($_SERVER['HTTPS']),
                'use_strict_mode' => true
            ]);
        }
    }

    /* 
     * Summary of register
     * @param $name
     * @param $email
     * @param $password
     * 
     * @return User
     */

    public function register(string $name, string $email, string $password): User
    {
        $this->validateEmail($email);
        $this->validatePassword($password);

        if ($this->userRepository->getUserByEmail($email)) {
            throw new Exception('Email déjà utilisé.');
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $user = new User(null, $name, $email, $hashedPassword);

        $userId = $this->userRepository->createUser($user);
        return new User($userId, $name, $email, $hashedPassword);
    }

    /* 
    
    
    */

    public function login(string $email, string $password): User
    {
        $this->validateEmail($email);

        $user = $this->userRepository->getUserByEmail($email);

        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new Exception('Identifiants invalides.');
        }

        // Protection contre fixation de session
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        return $user;
    }

    /* ==========================
       LOGOUT
       ========================== */

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    /* ==========================
       CSRF
       ========================== */

    public function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public function validateCsrfToken(string $token): void
    {
        if (
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $token)
        ) {
            throw new Exception('CSRF token invalide.');
        }
    }

    /* ==========================
       HELPERS SÉCURITÉ
       ========================== */

    private function validateEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email invalide.');
        }
    }

    private function validatePassword(string $password): void
    {
        if (strlen($password) < 8) {
            throw new Exception('Mot de passe trop court (8 caractères minimum).');
        }
    }
}
