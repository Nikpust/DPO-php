<?php

    namespace App\controllers;

    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    
    use App\core\BaseController;
    use App\models\Book;
    
    use Throwable;
    
    class BookController extends BaseController {

        public function add(): JsonResponse {
            $request = Request::createFromGlobals();
            $userId = $this->session->get('user_id');

            $title = trim($request->request->get('title'));
            $author = trim($request->request->get('author'));
            $readDate = $request->request->get('read-date');
            $coverFile = $request->files->get('cover');
            $bookFile = $request->files->get('file');
            $allowDownload = $request->request->has('allow_download') ? 1 : 0;

            if (!$coverFile || !$bookFile) {
                return new JsonResponse(['success' => false, 'message' => 'Ошибка загрузки файлов']);
            }

            if ($bookFile->getSize() > 5 * 1024 * 1024) {
                return new JsonResponse(['success' => false, 'message' => 'Размер файла книги превышает 5 МБ!']);
            }

            $coverPath = '';
            $filePath  = '';

            try {
                $coverPath = $this->storeFile($coverFile, 'covers', $userId);
                $filePath = $this->storeFile($bookFile,  'files',  $userId);

                $model = new Book();
                $model->add([
                    'title'          => $title,
                    'author'         => $author,
                    'read_date'      => $readDate,
                    'allow_download' => $allowDownload,
                    'cover_path'     => $coverPath,
                    'file_path'      => $filePath,
                    'user_id'        => $userId,
                ]);

                return new JsonResponse(['success' => true, 'message' => 'Книга добавлена']);
    
            } catch (Throwable $e) {
                if ($coverPath !== '') {
                    $this->rm($coverPath);
                }
                if ($filePath !== '') {
                    $this->rm($filePath);
                }
    
                return new JsonResponse(['success' => false, 'message' => $e->getMessage()]);
            }
        }

        public function get(): JsonResponse {
            $request = Request::createFromGlobals();
            $userId = $this->session->get('user_id');
            $payload = json_decode($request->getContent(), true);
            $bookId = $payload['id'];

            $model = new Book();
            $book = $model->getByBookId($bookId);
            
            return new JsonResponse(['success' => true, 'book' => $book]);
        }

        public function update(): JsonResponse {
            $request = Request::createFromGlobals();
            $userId = $this->session->get('user_id');
            $bookId = $request->request->get('id');
            
            $title = trim($request->request->get('title'));
            $author = trim($request->request->get('author'));
            $readDate = $request->request->get('read-date');
            $coverFile = $request->files->get('cover') ?: null;
            $bookFile = $request->files->get('file') ?: null;
            $allowDownload = $request->request->has('allow_download') ? 1 : 0;

            if ($bookFile != null && $bookFile->getSize() > 5 * 1024 * 1024) {
                return new JsonResponse(['success' => false, 'message' => 'Размер файла книги превышает 5 МБ!']);
            }
        
            $model = new Book();
            $current = $model->getByBookId($bookId);

            $coverPath = '';
            $filePath  = '';
    
            try {
                if ($coverFile) {
                    $coverPath = $this->storeFile($coverFile, 'covers', $userId);
                } else {
                    $coverPath = $current['book_cover_path'];
                }
                if ($bookFile) {
                    $filePath = $this->storeFile($bookFile, 'files', $userId);
                } else {
                    $filePath = $current['book_file_path'];
                }
    
                $model->update([
                    'book_id'        => $bookId,
                    'user_id'        => $userId,
                    'title'          => $title,
                    'author'         => $author,
                    'read_date'      => $readDate,
                    'allow_download' => $allowDownload,
                    'cover_path'     => $coverPath,
                    'file_path'      => $filePath,
                ]);

                if ($coverFile) {
                    $this->rm($current['book_cover_path']);
                }
                if ($bookFile) {
                    $this->rm($current['book_file_path']);
                }
    
                return new JsonResponse(['success' => true, 'message' => 'Книга обновлена']);
    
            } catch (Throwable $e) {
                if ($coverFile && $coverPath !== '') {
                    $this->rm($coverPath);
                }
                if ($bookFile && $filePath !== '') {
                    $this->rm($filePath);
                }
    
                return new JsonResponse(['success' => false,'message' => $e->getMessage()]);
            }
        }

        public function delete(): JsonResponse {
            $request = Request::createFromGlobals();
            $userId = $this->session->get('user_id');
            $payload = json_decode($request->getContent(), true);
            $bookId = $payload['id'];

            $model = new Book();
            $paths = $model->delete($bookId, $userId);

            $this->rm($paths['book_file_path']);
            $this->rm($paths['book_cover_path']);

            return new JsonResponse(['success' => true, 'message' => 'Книга удалена']);
        }

        private function storeFile(UploadedFile $file, string $subDir, int $userID): string {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $uploadRoot = "$root/uploads";

            if (!is_dir($uploadRoot)) {
                mkdir($uploadRoot, 0775, true);
            }

            $year = date('Y');
            $month = date('m');
            $day = date('d');
        
            $path = "$uploadRoot/$year/$month/$day/$userID/$subDir";
        
            if (!is_dir($path)) {
                mkdir($path, 0775, true);
            }

            $ext  = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $name = uniqid() . '.' . $ext;

            $file->move($path, $name);

            return "/uploads/$year/$month/$day/$userID/$subDir/$name";
        }
        
        private function rm(string $path): void {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $path;
            if (is_file($fullPath)) {
                unlink($fullPath);
            }
        }

    }
    
?>