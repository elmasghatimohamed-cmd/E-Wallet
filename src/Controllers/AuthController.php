<?php

namespace Src\Controllers;

use Src\Services\UserService;
use Exception;

class AuthController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Affiche la page d'inscription
     */
    public function showRegisterForm(): void
    {
        $csrfToken = $this->userService->getCsrfToken();
        require_once __DIR__ . '/../../views/auth/register.php';
    }

    /**
     * Traite l'inscription
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        try {
            // Validation CSRF
            $this->userService->validateCsrfToken($_POST['csrf_token'] ?? '');

            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validation des champs
            if (empty($name) || empty($email) || empty($password)) {
                throw new Exception('Tous les champs sont obligatoires.');
            }

            if ($password !== $confirmPassword) {
                throw new Exception('Les mots de passe ne correspondent pas.');
            }

            // Enregistrement de l'utilisateur
            $user = $this->userService->register($name, $email, $password);

            // Connexion automatique après inscription
            $this->userService->login($email, $password);

            // Redirection vers le tableau de bord
            $_SESSION['success'] = 'Inscription réussie ! Bienvenue ' . htmlspecialchars($name) . ' !';
            header('Location: /dashboard');
            exit;

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old'] = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? ''
            ];
            header('Location: /register');
            exit;
        }
    }

    /**
     * Affiche la page de connexion
     */
    public function showLoginForm(): void
    {
        $csrfToken = $this->userService->getCsrfToken();
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    /**
     * Traite la connexion
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        try {
            // Validation CSRF
            $this->userService->validateCsrfToken($_POST['csrf_token'] ?? '');

            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                throw new Exception('Email et mot de passe sont obligatoires.');
            }

            // Connexion de l'utilisateur
            $user = $this->userService->login($email, $password);

            $_SESSION['success'] = 'Connexion réussie ! Bienvenue ' . htmlspecialchars($user->getName()) . ' !';
            header('Location: /dashboard');
            exit;

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old'] = ['email' => $_POST['email'] ?? ''];
            header('Location: /login');
            exit;
        }
    }

    /**
     * Déconnexion
     */
    public function logout(): void
    {
        $this->userService->logout();
        $_SESSION['success'] = 'Vous avez été déconnecté avec succès.';
        header('Location: /login');
        exit;
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Middleware pour protéger les routes
     */
    public static function requireAuth(): void
    {
        if (!self::isAuthenticated()) {
            $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page.';
            header('Location: /login');
            exit;
        }
    }
}