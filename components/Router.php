<?php

class Router //задача роутера прочитать маршруты и помнить на врем выполнения кода
{

    private $routes; //для хранения маршрутов

    public function __construct() // в конструкторе мы читаем и запоминаем роуты
    {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include($routesPath);  //присваиваем свойству routes массив, который хранится в файле routes.php
    }

    /**
     * Returns request string
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {         // возвращает get-запрос
            return trim($_SERVER['REQUEST_URI'], '/'); //отрубает символ от начала
        }
    }

    public function run() // анализ запроса и передача управления
    {
        // Получить строку запроса
        $uri = $this->getURI();

        // Проверить наличие такого запроса в routes.php
        foreach ($this->routes as $uriPattern => $path) {  //$uriPattern - строка запроса, $path - имя контроллера и экшена

            // Сравниваем $uriPattern и $uri
            if (preg_match("~$uriPattern~", $uri)) { //если $uri присутствует в массиве с роутами, то условие выполняется
                
                // Получаем внутренний путь из внешнего согласно правилу.
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri); 
                                
                // Определить контроллер, action, параметры

                $segments = explode('/', $internalRoute); //отделяем контроллер от экшена

                $controllerName = array_shift($segments) . 'Controller'; // удаляем первый элемент массива
                $controllerName = ucfirst($controllerName); //делаем первую букву в названии контроллера заглавной

                $actionName = 'action' . ucfirst(array_shift($segments)); // удаляет из массива и возвращает название экшена
                             
                $parameters = $segments;
                
                // Подключить файл класса-контроллера
                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }

                // Создать объект, вызвать метод (т.е. action)
                $controllerObject = new $controllerName;
                

                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
                
                
                if ($result != null) {
                    break;
                }
            }
        }
    }

}
