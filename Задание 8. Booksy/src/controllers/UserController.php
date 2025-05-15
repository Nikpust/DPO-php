<?php

    namespace App\controllers;

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Session\Session;
    
    use App\models\User;
    use App\core\BaseController;

    class UserController extends BaseController {

        public function index(): Response {
            $path = (Request::createFromGlobals())->getPathInfo();
            if ($path === '/login') {
                $mode = 'sign-in';
            } elseif ($path === '/register') {
                $mode = 'sign-up';
            }

            return new Response($this->twig->render('auth.twig', ['mode' => $mode]));
        }
        
        public function register(): JsonResponse {
            $request = Request::createFromGlobals();
            $userModel = new User();
            $result = $userModel->register($request);

            return new JsonResponse($result);
        }
        
        public function login(): JsonResponse {
            $request = Request::createFromGlobals();
            $userModel = new User();
            $result = $userModel->login($request, $this->session);

            return new JsonResponse($result);
        }

        public function logout(): Response {
            $this->session->invalidate();
            return new Response('', 302, ['Location' => '/']);
        }

    }

?>