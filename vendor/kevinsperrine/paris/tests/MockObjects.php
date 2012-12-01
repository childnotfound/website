<?php

/**
 *
 * Mock version of the PDOStatement class.
 *
 */
class DummyPDOStatement extends PDOStatement
{
    private $current_row = 0;

    private $current_statement = null;

    public function __constructor($statement)
    {
        $this->current_statement = $statement;

        parent::__constructor($statement);
    }

    /**
     * Return some dummy data
     */
    public function fetch($fetch_style=PDO::FETCH_BOTH, $cursor_orientation=PDO::FETCH_ORI_NEXT, $cursor_offset=0)
    {
        // Hack to get searched for values and if searching for id = 9999
        // return false, as if it doesn't exist.
        // 
        $mostRecent = array_pop(Orm::getQueryLog());
        if (stripos($mostRecent, "`id` = '9999'") !== false) {
            return false;
        }

        if ($this->current_row == 5) {
            return false;
        } else {
            $this->current_row++;

            return array('name' => 'Fred', 'age' => 10, 'id' => '1');
        }
    }
}

/**
 *
 * Mock database class implementing a subset
 * of the PDO API.
 *
 */
class DummyPDO extends PDO
{
    /**
     * Return a dummy PDO statement
     */
    public function prepare($statement, $driver_options=array())
    {
        $this->last_query = new DummyPDOStatement($statement);

        return $this->last_query;
    }
}
