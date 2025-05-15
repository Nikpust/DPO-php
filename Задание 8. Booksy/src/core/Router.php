<?php

    namespace App\core;

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Exception\MethodNotAllowedException;
    use Symfony\Component\Routing\Exception\ResourceNotFoundException;
    use Symfony\Component\Routing\Matcher\UrlMatcher;
    use Symfony\Component\Routing\RequestContext;
    use Symfony\Component\Routing\Route;
    use Symfony\Component\Routing\RouteCollection;

    use Throwable;

    class Router {

        private RouteCollection $routes;
        private RequestContext $context;
        private Request $request;

        public function __construct() {
            $this->routes = new RouteCollection();          # Создаем перечень маршрутов
            $this->request = Request::createFromGlobals();  # Получаем всю информацию о запросе
            $this->context = new RequestContext();          # Создаем контекст запроса (для конкретной выборки)
            $this->context->fromRequest($this->request);    # Производим выборку для маршрутизатора
        }

        public function addRoute(string $name, string $path, array $controller, string $method): void {
            $route = new Route($path, ['_controller' => $controller]);  # Создаем маршрут
            $route->setMethods([$method]);                              # Устанавливаем метод маршрута
            $this->routes->add($name, $route);                          # Добавляем маршрут в перечень маршрутов
        }

        public function dispatch(): void {
            $matcher = new UrlMatcher($this->routes, $this->context);   # Создаем сопоставитель маршрутов

            try {
                $controllerSpec = $matcher->match($this->request->getPathInfo());       # Сопоставляем текущий путь с маршрутом
                $response = $this->executeController($controllerSpec['_controller']);   # Получаем контроллер и вызываем его метод

                if ($response instanceof Response) {
                    $response->send();
                } else {
                    echo $response;
                }

            } catch (MethodNotAllowedException) {
                http_response_code(405);
                echo 'Метод не разрешен';
            } catch (ResourceNotFoundException) {
                http_response_code(404);
                echo 'Страница не найдена';
            } catch (Throwable $e) {
                http_response_code(500);
                echo 'Ошибка сервера: ' . $e->getMessage();
            }
        }

        private function executeController(array $controllerSpec) {
            [$сlass, $method] = $controllerSpec;
            $controller = new $сlass();
            return $controller->$method();
        }
        
    }

?>