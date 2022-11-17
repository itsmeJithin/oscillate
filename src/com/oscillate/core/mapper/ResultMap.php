<?php


namespace com\oscillate\core\mapper;

/**
 * @author Jithin Vijayan
 * Class ResultMap
 * @package com\oscillate\core\mapper
 */
class ResultMap
{

    /**
     * Id of resultmap
     * @var string
     */
    public $id;

    /**
     * Class name of mapper class.Specify the namespace also.
     * eg:com\oscillate\core\dto\MapperClass
     * @var string
     */
    public $class;

    /**
     * Class properties mapping details array
     * @var array
     */
    public $results;
    /**
     * Primary key property of a class
     * @var string
     */
    public $primaryKey;

    /**
     * Sql primary key column name
     * @var string
     */
    public $primaryKeyColumn;

    public function __construct($id = null, $class = null,$primaryKey = null,$primaryKeyColumn=NULL, $results = null)
    {
        $this->id = $id;
        $this->class = $class;
        $this->results = $results;
        $this->primaryKey = $primaryKey;
        $this->primaryKeyColumn = $primaryKeyColumn;
    }
}