<?php

    namespace App\models;

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Session\Session;

    use PDO;
    use PDOException;

    use App\core\Database;

    class User {
        
        private PDO $db;

        public function __construct() {
            $this->db = Database::connect();
        }

        public function register(Request $request): array {
            $fullname = trim($request->request->get('name', ''));
            $email = trim($request->request->get('email', ''));
            $password = trim($request->request->get('password', ''));

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Невалидный email'];
            }

            if (strlen($password) < 6) {
                return ['success' => false, 'message' => 'Пароль должен содержать не менее 6 символов'];
            }

            try {
                $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE user_email = :email");
                $stmt->execute([':email' => $email]);

                if ($stmt->fetchColumn() > 0) {
                    return ['success' => false, 'message' => 'Email уже используется'];
                }

                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("
                    INSERT INTO users (user_created_at, user_fullname, user_email, user_password)
                    VALUES (NOW(), :name, :email, :pass)
                ");
                $stmt->execute([
                    ':name'  => $fullname,
                    ':email' => $email,
                    ':pass'  => $hashed
                ]);

                return ['success' => true, 'message' => 'Регистрация успешна!'];

            } catch (PDOException $e) {
                return ['success' => false, 'message' => 'Ошибка БД: ' . $e->getMessage()];
            }
        }

        public function login(Request $request, Session $session): array {
            
            $email = trim($request->request->get('email', ''));
            $password = $request->request->get('password', '');

            try {
                $stmt = $this->db->prepare("SELECT * FROM users WHERE user_email = :email");
                $stmt->execute([':email' => $email]);

                $user = $stmt->fetch();

                if (!$user || !password_verify($password, $user['user_password'])) {
                    return ['success' => false, 'message' => 'Неверный email или пароль'];
                }

                $session->set('user_id', $user['user_id']);
                $session->set('user_fullname', $user['user_fullname']);
                $session->set('user_email', $user['user_email']);

                return ['success' => true, 'message' => 'Вход успешен!', 'redirect' => '/'];
                
            } catch (PDOException $e) {
                return ['success' => false, 'message' => 'Ошибка БД: ' . $e->getMessage()];
            }
        }

    }

?>