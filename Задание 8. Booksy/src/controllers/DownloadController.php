<?php

    namespace App\Controllers;

    use Symfony\Component\HttpFoundation\BinaryFileResponse;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    
    use App\core\BaseController;
    use App\models\Book;

    class DownloadController extends BaseController {

        public function checkDownloadAccess(): JsonResponse {
            $request = Request::createFromGlobals();
            $payload = json_decode($request->getContent(), true);
            $userId = $this->session->get('user_id');
            $bookId = $payload['id'];

            $model = new Book();
            $book = $model->getByBookId($bookId);

            # Если пользователь владелец книги
            if ($userId && $book['user_id'] == $userId) {
                return new JsonResponse(['success' => true]);
            }

            # Иначе проверяем разрешение на скачивание
            if ($book['book_allow_download'] != 1) {
                return new JsonResponse(['success' => false, 'message' => 'Пользователь ограничил доступ к загрузке']);
            }
        
            return new JsonResponse(['success' => true]);
        }

        public function download(): Response {
            $request = Request::createFromGlobals();
            $bookId = $request->request->get('bookId');
        
            $model = new Book();
            $book = $model->getByBookId($bookId);
        
            $filePath = $_SERVER['DOCUMENT_ROOT'] . $book['book_file_path'];
            
            if (!file_exists($filePath)) {
                return new JsonResponse(['success' => false, 'message' => 'Непредвиденная ошибка']);
            }
        
            return new BinaryFileResponse($filePath, 200, [
                'Content-Type' => 'application/octet-stream',                                   # Бинарный файл, обратока как скачиваемого объекта
                'Content-Disposition' => 'attachment; filename="' . basename($filePath) . '"'   # Файл требуется скачать, а не открывать
            ]);
        }
        
    }

?>