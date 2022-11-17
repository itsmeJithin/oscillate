<?php


namespace com\oscillate\core\util;

use com\oscillate\core\dto\MySqlResult;

/**
 * @author Jithin Vijayan
 * Class MySqlUtil
 * @package com\oscillate\core\util
 */
class MySqlUtil
{
    public static function closeConnection($connection)
    {
        return mysqli_close($connection);
    }
    /**
     * check sql result has records/rows
     *
     * @param mysqlresult $sqlResult
     * @return boolean true if yes else false
     */
    public static function hasRows($sqlResult)
    {
        return self::getNumOfRows($sqlResult) > 0 ? true : false;
    }

    /**
     * get num of rows in mysql result
     *
     * @param mysqlresult $sqlResult
     * @return integer number of roes
     */
    public static function getNumOfRows($sqlResult)
    {
        return mysqli_num_rows($sqlResult);
    }

    /**
     * get num of fields in mysql result
     *
     * @param mysqlresult $sqlResult
     * @return integer number of fields
     */
    public static function getNumOfFields($sqlResult)
    {
        return mysqli_num_fields($sqlResult);
    }

    /**
     * fetch rows in mysql result
     *
     * @param mysqlresult $sqlResult
     * @return mixed
     */
    public static function fetchRow($sqlResult)
    {
        return mysqli_fetch_row($sqlResult);
    }

    /**
     * fetch array in mysql result
     *
     * @param mysqlresult $sqlResult
     * @return array
     */
    public static function fetchArray($sqlResult)
    {
        return mysqli_fetch_array($sqlResult);
    }

    /**
     * fetch object in mysql result
     *
     * @param mysqlresult $sqlResult
     * @return object
     */
    public static function fetchObject($sqlResult, $class_name = NULL, $params = NULL)
    {
        return mysqli_fetch_object($sqlResult);
    }

    /**
     * get insert id of current connection
     *
     * @param connection $connection
     * @return id
     */
    public static function getInsertId($connection)
    {
        return mysqli_insert_id($connection);
    }

    /**
     * mysql real escape string
     *
     * @param string $string
     */
    public static function realEscapeString($string)
    {
        return mysqli_real_escape_string($string);
    }

}