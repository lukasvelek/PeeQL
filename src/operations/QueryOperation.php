<?php

namespace PeeQL\Operations;

/**
 * QueryOperation defines operation of type Query
 * 
 * @author Lukas Velek
 */
class QueryOperation extends AOperation {
    /**
     * Class constructor
     * 
     * @param string $name Query name
     */
    public function __construct(string $name) {
        parent::__construct(self::OPERATION_TYPE_QUERY, $name);
    }
    
    protected function processJson() {
        parent::processJson();

        $path = sprintf('definition.%s.%s', $this->handlerName, $this->handlerMethodName);

        $this->selectCols = $this->get($path . '.cols');
        
        // Conditions
        $conditions = $this->get($path . '.conditions');

        if($conditions !== null) {
            foreach($conditions as $condition) {
                $this->conditions->addCondition($condition['col'], $condition['value'], $condition['type']);
            }
        }

        // Ordering
        $orderBy = $this->get($path . '.orderBy');

        if($orderBy !== null) {
            foreach($orderBy as $key => $value) {
                $this->orderBy[$key] = $value;
            }
        }
    }
}

?>