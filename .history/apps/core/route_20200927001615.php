<?php
class Route {
    /**
     * @var
     * void
     */
    // protected $routes=[];

    public function __construct($url = null) {
        include_once APP_PATH . '/routes/routes.php';
        global $routes;
        if(!IS_ROUTE)
            return false;
        elseif(IS_ROUTE){
            var_dump($routes);die;
            if(!isset($routes[$url]))
                return false;
            $res = $this->run_routes($url);
            return $res;
        }
        
        // var_dump($url);die;
    }

    static function get($route, $function){
        global $routes;

        // sanitasi
        $r = self::sanitasi_route($route);
        var_dump($r);
        $routes[$r[0]] = $function;
    }

    function run_routes($route){
        global $routes;
        global $params_count;
        var_dump($params_count);die;
        $url = explode('/', $route);
        if(is_callable($routes[$route])){
            $routes[$route]();
        }
        else{
            $controller = explode("@", $routes[$route]);
            $class = '';
            require_once APP_PATH . 'apps/controller/' . $controller[0] . '.php';
            $class = new $controller[0];
            call_user_func_array([$class, $controller[1]], []);
        }

        return true;
    }

    private function sanitasi_route($route){
        // $url = str_replace('}', '', $route);
        $url = explode('/', $route);
        $url_length = count($url) - 1;
        $params_count = preg_match_all('/.[:]/i', $route, $result);
        $url_count = $url_length - $params_count;
        $url_valid = '';
        var_dump($url[0]);

        for ($i=0; $i <= $url_count; $i++) { 
            if($i == 0)
                $url_valid .= $url[$i];
            else
                $url_valid .= '/' . $url[$i];
        }
        return [$url_valid, $url_count, $params_count];
    }
}