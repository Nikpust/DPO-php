<?php

    use App\controllers\HeroController;
    use App\controllers\BookController;
    use App\controllers\UserController;
    use App\controllers\ProfileController;
    use App\controllers\DownloadController;

    $router->addRoute('hero', '/', [HeroController::class, 'index'], 'GET');

    $router->addRoute('login_form', '/login', [UserController::class, 'index'], 'GET');
    $router->addRoute('login_submit', '/login', [UserController::class, 'login'], 'POST');
    
    $router->addRoute('register_form', '/register', [UserController::class, 'index'], 'GET');
    $router->addRoute('register_submit', '/register', [UserController::class, 'register'], 'POST');
    
    $router->addRoute('logout', '/logout', [UserController::class, 'logout'], 'GET');

    $router->addRoute('profile', '/profile', [ProfileController::class, 'index'], 'GET');

    $router->addRoute('add_book', '/profile/add-book', [BookController::class, 'add'], 'POST');
    $router->addRoute('get_book', '/profile/get-book', [BookController::class, 'get'], 'POST');
    $router->addRoute('update_book', '/profile/update-book', [BookController::class, 'update'], 'POST');
    $router->addRoute('delete_book', '/profile/delete-book', [BookController::class, 'delete'], 'DELETE');
    
    $router->addRoute('check_download', '/check-book-download', [DownloadController::class, 'checkDownloadAccess'], 'POST');
    $router->addRoute('download_book', '/download-book', [DownloadController::class, 'download'], 'POST');

?>