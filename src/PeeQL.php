<?php

namespace PeeQL;

use PeeQL\Parser\PeeQLParser;
use PeeQL\Router\PeeQLRouter;

/**
 * This is the main PeeQL class that has definitions of all important methods and functions
 * 
 * @author Lukas Velek
 */
class PeeQL {
    private ?PeeQLRouter $router;
    private PeeQLParser $parser;

    /**
     * Class constructor
     */
    public function __construct() {
        $this->parser = new PeeQLParser();

        $this->router = null;
    }

    /**
     * Returns an existing or a new instance of PeeQLRouter for route definition
     */
    public function getRouter(): PeeQLRouter {
        if($this->router === null) {
            $this->router = new PeeQLRouter();
        }

        $router = &$this->router;

        return $router;
    }

    /**
     * Processes the given JSON $json query, executes it and returns the result
     * 
     * @param string $json JSON query
     */
    public function execute(string $json): mixed {
        return $this->parser->parse($this->router, $json);
    }
}

?>