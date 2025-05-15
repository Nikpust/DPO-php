<?php

    namespace App\controllers;

    use Symfony\Component\HttpFoundation\Response;

    use App\core\BaseController;
    use App\models\Book;

    class HeroController extends BaseController {
        
        public function index(): Response {
            $model = new Book();
            $books = $model->getAll();
            return new Response($this->twig->render('hero.twig', ['books' => $books]));
        }
        
    }

?>