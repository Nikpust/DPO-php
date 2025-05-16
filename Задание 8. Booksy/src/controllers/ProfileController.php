<?php

    namespace App\controllers;

    use Symfony\Component\HttpFoundation\Response;
    
    use App\core\BaseController;
    use App\models\Book;

    class ProfileController extends BaseController {

        public function index(): Response {
            if ($this->session->get('user_id') == null) {
                return new Response('', 302, ['Location' => '/login']);
            } else {
                $model = new Book();
                $books = $model->getByUserId($this->session->get('user_id'));
                return new Response($this->twig->render('profile.twig', ['books' => $books]));
            }
        }

    }

?>