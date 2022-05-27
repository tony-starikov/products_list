<?php


class Router
{

    private $routes;

    public function __construct()
    {
        $routesPath = ROOT.'/config/routes.php';
        $this->routes = require_once($routesPath);
    }

    /**
     * Returns request string
     *
     * @return string
     */
    private function getUri()
    {
        $uri = '';

        if (!empty($_SERVER['REQUEST_URI'])) {
            $uri = trim($_SERVER['REQUEST_URI'], '/');
        }

        return $uri;
    }

    public function run()
    {
        // Get uri
        $uri = $this->getUri();

        // Find this request in routes
        foreach ($this->routes as $requestUriPattern => $path) {

            // Compare uri pattern from routes and uri from request
            if (preg_match("~$requestUriPattern~", $uri)) {

                $internalRoute = preg_replace("~$requestUriPattern~", $path, $uri);

                $segments = explode('/', $internalRoute);

                $controllerName = array_shift($segments) . 'Controller';

                $controllerName = ucfirst($controllerName);

                $actionName = 'action' . ucfirst(array_shift($segments));

                $parameters = $segments;

                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    require_once($controllerFile);
                }

                $controllerObject = new $controllerName;

                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);

                if ($result != null) {
                    break;
                }
            } else {
                $controllerName = 'HomeController';
                $actionName = 'actionMain';

                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    require_once($controllerFile);
                }

                $controllerObject = new $controllerName;

                $result = call_user_func_array(array($controllerObject, $actionName), []);

                if ($result != null) {
                    break;
                }
            }

        }


    }
}