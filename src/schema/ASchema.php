<?php

namespace PeeQL\Schema;

use PeeQL\Operations\Conditions\QueryConditionList;
use PeeQL\Operations\QueryOperation;

/**
 * Common class for all schema definition classes
 * 
 * @author Lukas Velek
 */
abstract class ASchema {
    protected string $name;

    protected array $visibleColumns;
    protected array $filterableColumns;
    protected array $sortableColumns;
    protected array $requiredFilterColumns;

    private bool $isDefined;

    /**
     * Class constructor
     * 
     * @param string $name Schema name
     */
    public function __construct(string $name) {
        $this->name = $name;
        
        $this->visibleColumns = [];
        $this->filterableColumns = [];
        $this->sortableColumns = [];
        $this->requiredFilterColumns = [];
        $this->isDefined = false;
    }

    /**
     * Adds a required filter column
     * 
     * If the query doesn't have a condition with this column, it will fail
     * 
     * @param string $name Column name
     */
    public function addRequiredFilterColumn(string $name) {
        $this->requiredFilterColumns[] = $name;
    }

    /**
     * Adds column to schema
     * 
     * @param string $name Column name
     * @param bool $filterable Is column filterable?
     * @param bool $sortable Is column sortable?
     */
    protected function addColumn(string $name, bool $filterable = true, bool $sortable = true) {
        $this->visibleColumns[] = $name;
        if($filterable) {
            $this->filterableColumns[] = $name;
        }
        if($sortable) {
            $this->sortableColumns[] = $name;
        }
    }

    /**
     * Adds multiple columns to schema and implicitly allows filtering and sorting for them
     * 
     * @param array $columnNames Column names
     */
    protected function addMultipleColumns(array $columnNames) {
        foreach($columnNames as $column) {
            $this->addColumn($column);
        }
    }


    /**
     * Removes a column from filterable columns
     * 
     * @param string $name Column name
     */
    protected function setNotFilterableColumn(string $name) {
        $_index = null;
        foreach($this->filterableColumns as $index => $column) {
            if($column == $name) {
                $_index = $index;
            }
        }

        if($_index !== null) {
            unset($this->filterableColumns[$_index]);
        }
    }

    /**
     * Removes a column from sortable columns
     * 
     * @param string $name Column name
     */
    protected function setNotSortableColumn(string $name) {
        $_index = null;
        foreach($this->sortableColumns as $index => $column) {
            if($column == $name) {
                $_index = $index;
            }
        }

        if($_index !== null) {
            unset($this->sortableColumns[$_index]);
        }
    }

    /**
     * Creates schema for browsing and returns it as JSON
     */
    public function createSchemaForBrowsing(): string {
        $schema = [
            'name' => $this->name,
            'visibleColumns' => $this->visibleColumns,
            'filterableColumns' => $this->filterableColumns,
            'sortableColumns' => $this->sortableColumns,
            'requiredColumnsForFiltering' => $this->requiredFilterColumns
        ];

        return json_encode($schema);
    }

    /**
     * Validates the QueryOperation against the schema and returns a validated instance
     * 
     * @param QueryOperation $operation Initial operation
     */
    public function validate(QueryOperation $operation): QueryOperation {
        if(!$this->isDefined) {
            $this->define();
        }

        $visibleColumns = [];
        foreach($operation->getColumns() as $column) {
            if(in_array($column, $this->visibleColumns)) {
                $visibleColumns[] = $column;
            }
        }

        $conditionList = new QueryConditionList();
        foreach($operation->getConditionsAsArray() as $condition) {
            /**
             * @var \PeeQL\Operations\Conditions\QueryCondition $condition
             */

            if(in_array($condition->getColumnName(), $this->filterableColumns)) {
                $conditionList->addObjectCondition($condition);
            }
        }

        return QueryOperation::cloneAfterValidation($operation, $visibleColumns, $conditionList);
    }

    /**
     * Contains definitions of columns, conditions, etc.
     */
    protected abstract function define();
}

?>