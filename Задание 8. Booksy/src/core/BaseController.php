<?php

    namespace App\core;

    use Symfony\Component\HttpFoundation\Session\Session;
    use Twig\Loader\FilesystemLoader;
    use Twig\Environment;
    
    abstract class BaseController {

        protected Environment $twig;
        protected Session $session;

        public function __construct() {
            $loader = new FilesystemLoader(__DIR__ . '/../views');      # Загрузчик Twig-шаблонов (задаем путь, где искать)
            $this->twig = new Environment($loader);                     # Создаем объект шаблонизатора для рендеринга шаблона

            $this->session = new Session();

            if (!$this->session->isStarted()) {
                $this->session->start();
            }

            # Передаем данные о сессии во все шаблоны (доступ по переменный session)
            $this->twig->addGlobal('session', $this->session->all());
        }

    }

?>