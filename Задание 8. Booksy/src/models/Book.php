<?php

    namespace App\models;

    use PDO;
    use PDOException;

    use App\core\Database;

    class Book {
        private PDO $db;

        public function __construct() {
            $this->db = Database::connect();
        }

        public function getAll(): array {
            $stmt = $this->db->prepare("SELECT * FROM books ORDER BY book_created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public function getByUserId(int $userId): array {
            $stmt = $this->db->prepare("SELECT * FROM books WHERE user_id = :user_id ORDER BY book_read_date DESC");
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll();
        }

        public function getByBookId(int $bookId): array {
            $stmt = $this->db->prepare("
                SELECT books.*, users.user_fullname 
                FROM books
                JOIN users ON books.user_id = users.user_id
                WHERE books.book_id = :book_id
            ");
            $stmt->execute([':book_id' => $bookId]);
            return $stmt->fetch();
        }

        public function add(array $data): void {
            try {
                $stmt = $this->db->prepare("
                    INSERT INTO books 
                    (book_created_at, book_updated_at, book_title, book_author, book_cover_path, book_file_path, book_read_date, book_allow_download, user_id) 
                    VALUES (NOW(), NOW(), :title, :author, :cover_path, :file_path, :read_date, :allow_download, :user_id)
                    ");
                $stmt->execute([
                    ':title'          => $data['title'],
                    ':author'         => $data['author'],
                    ':cover_path'     => $data['cover_path'],
                    ':file_path'      => $data['file_path'],
                    ':read_date'      => $data['read_date'],
                    ':allow_download' => $data['allow_download'],
                    ':user_id'        => $data['user_id'],
                ]);
            } catch (PDOException $e) {
                throw $e;
            }
        }

        public function update(array $data): void {
            try {
                $stmt = $this->db->prepare("
                    UPDATE books 
                    SET book_updated_at = NOW(), book_title = :title, book_author = :author, book_cover_path = :cover_path, book_file_path = :file_path, book_read_date = :read_date, book_allow_download = :allow_download 
                    WHERE book_id = :book_id AND user_id = :user_id
                ");
                $stmt->execute([
                    ':book_id'        => $data['book_id'],
                    ':user_id'        => $data['user_id'],
                    ':title'          => $data['title'],
                    ':author'         => $data['author'],
                    ':cover_path'     => $data['cover_path'],
                    ':file_path'      => $data['file_path'],
                    ':read_date'      => $data['read_date'],
                    ':allow_download' => $data['allow_download'],
                ]);
            } catch (PDOException $e) {
                throw $e;
            }
        }

        public function delete(int $bookId, int $userId): array {
            $stmt = $this->db->prepare("SELECT book_cover_path, book_file_path FROM books WHERE book_id = :id AND user_id = :user_id");
            $stmt->execute([':id' => $bookId, ':user_id' => $userId]);
            $pathData = $stmt->fetch();

            $stmt = $this->db->prepare("DELETE FROM books WHERE book_id = :id AND user_id = :user_id");
            $stmt->execute([':id' => $bookId, ':user_id' => $userId]);

            return $pathData;
        }

    }

?>