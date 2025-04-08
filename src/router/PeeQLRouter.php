<?php

namespace PeeQL\Router;

/**
 * PeeQLRouter contains route definitions to database query handlers
 * 
 * @author Lukas Velek
 */
class PeeQLRouter {
    private array $routes;

    /**
     * Class constructor
     */
    public function __construct() {
        $this->routes = [];
    }
}

?>