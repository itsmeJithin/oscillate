<?php


namespace com\oscillate\core\mapper;


class Result
{
    /**
     * Result Type for mapping Integer type from column values.
     *
     */
    const INT = "INT";
    /**
     * Result Type for mapping string type from column values.
     *
     */
    const STRING = "STRING";
    /**
     * Result Type for mapping float type from column values.
     *
     */
    const FLOAT = "FLOAT";
    /**
     * Result Type for mapping double type from column values.
     *
     */
    const DOUBLE = "DOUBLE";
    /**
     * Result Type for mapping boolean type from column values.
     *
     */
    const BOOLEAN = "BOOLEAN";

    /**
     * Result Type for mapping object from column values.
     *
     */
    const OBJECT = "OBJECT";
    /**
     *
     *Result Type for mapping array of object.This is can be used for mapping associations
     */
    const OBJECT_ARRAY = "OBJECT_ARRAY";
    /**
     * Result Type for convert string value to array .
     * This can be useful when string value contains more than one value separator by any separator(eg.',',':' ..etc).
     * You must mention the separator used in the string value to convert the same.
     *
     */
    const ARRAY_FROM_STRING = "ARRAY_FROM_STRING";

    /**
     * Result Type for Parse json value(stored in DB) and map to class property
     *
     */
    const OBJECT_FROM_JSON = "OBJECT_FROM_JSON";


    /**
     * Class property name
     * @var string
     */
    public $property;

    /**
     * SQL column name
     * @var string
     */
    public $column;

    /**
     *Class mapper
     * @var ResultMap
     */
    public $resultMap;


    /**
     * Type of class property.
     * eg:OBJECT,OBJECT_ARRAY
     * If not defined or null,sql column mapped to property
     * else look for resultMap of specified result type.
     * @var string
     */
    public $resultType;
    /**
     * Separator used in string value .This will help for converting object array from string value.
     * You must specify the separator if the result type is 'ARRAY_FROM_STRING'
     * @var string
     */
    public $arrayFromStringSeparator;


    public function __construct($property = null, $column = null,$resultType = null, $resultMap = null,$arrayFromStringSeparator=",")
    {
        $this->property = $property;
        $this->column = $column;
        $this->resultMap = $resultMap;
        $this->resultType = $resultType;
        $this->arrayFromStringSeparator = $arrayFromStringSeparator;
    }
}