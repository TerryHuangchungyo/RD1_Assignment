<?php
class API {
    public function __construct() {
        if( isset($_GET["url"])) {
            $url = explode("/",rtrim( $_GET["url"], "/"));
            $controllerName = "{$url[0]}Controller";
            require_once "controllers/$controllerName.php";
            $controller = new $controllerName;
            $method = $url[1];
            unset($url[0]);
            unset($url[1]);
            $param = $url ? array_values($url) : Array();
            call_user_func_array( array( $controller, $method ), $param );
        } else {
            header( "HTTP/1.1 404 Not Found" );
        }
    }
}