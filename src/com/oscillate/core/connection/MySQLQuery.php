<?php


namespace com\oscillate\core\connection;

use com\oscillate\core\dto\MySqlResult;
use com\oscillate\core\exception\SQLException;
use com\oscillate\core\util\MapperUtil;
use com\oscillate\core\util\ObjectUtil;
use Exception;

/**
 * @author Jithin Vijayan
 * Class MySQLQuery
 * @package com\oscillate\core\connection
 */
class MySQLQuery implements IMySQLConnector
{
    public $connection;

    private function getConnection()
    {
        $this->establishConnection();
        return $this->connection;
    }

    public function establishConnection()
    {
        // TODO: Implement establishConnection() method.
    }

    /**
     * mysql real escape object
     *
     * @param object $object
     * @return object|string
     */
    public function realEscapeObject($object)
    {
        if (!empty($object)) {
            if (is_array($object) || is_object($object)) {
                foreach ($object as $key => $value) {
                    if (is_array($value)) {
                        $object->{$key} = $this->realEscapeArray($value);
                    } else if (is_object($value)) {
                        $object->{$key} = $this->realEscapeObject($value);
                    } else {
                        $object->{$key} = $this->realEscapeString($value);
                    }
                }
            } else {
                return $this->realEscapeString($object);
            }
        }
        return $object;
    }

    /**
     * mysql real escape array of objects
     *
     * @param array $objArray
     * @return array
     */
    public function realEscapeArray($objArray)
    {
        if (!empty($objArray)) {
            foreach ($objArray as $key => $object) {
                $objArray[$key] = $this->realEscapeObject($object);
            }
        }
        return $objArray;
    }

    /**
     * mysql real escape string
     *
     * @param $string
     * @param bool $encodeHtmlSpecialChars Escape html special characters. Only disable to handle old implementations.
     * @return string
     */
    public function realEscapeString($string, $encodeHtmlSpecialChars = true)
    {
        if ($encodeHtmlSpecialChars)
            return mysqli_real_escape_string($this->getConnection(), $this->escapeHtmlSpecialChars($string));
        else
            return mysqli_real_escape_string($this->getConnection(), trim($string));
    }

    /**
     * @param $string
     * @return string
     */
    private function escapeHtmlSpecialChars($string)
    {
        return trim(htmlspecialchars($string, ENT_COMPAT | ENT_HTML401, ini_get("default_charset"), false));
    }

    /**
     * Method for executing the mysql query
     *
     * @param string $sql
     *            : sql to execute
     * @param boolean $isReturnKey
     *            : If you
     *            need id in case of add operation specify
     *            true to get the last inserted id
     * @return MySqlResult|null
     * @throws Exception
     */
    protected function executeQuery($sql, $isReturnKey = FALSE)
    {
        $sqlResult = NULL;
        //Establish the connection by invoking establishConnection which is defined in IMySqlConnector .
        $this->establishConnection();

        $result = mysqli_query($this->connection, $sql);

        try {
            $sqlResult = $this->processResult($result, $isReturnKey);
        } catch (Exception $e) {
            throw $e;
        }

        return $sqlResult;
    }

    /**
     * Method for executing the mysql query and return result as object
     *
     * @param string $sql
     *            : sql to execute
     * @param boolean $isReturnKey
     *            : If you
     *            need id in case of add operation specify
     *            true to get the last inserted id
     * @return Object|integer - if $isReturnKey is TRUE then return id else query result object
     * @throws Exception
     */
    protected function executeQueryForObject($sql, $isReturnKey = FALSE, $mapper = null)
    {
        $sqlResult = NULL;
        $object = NULL;
        $result = mysqli_query($this->getConnection(), $sql);
        try {
            $sqlResult = $this->processResult($result, $isReturnKey);

            if ($isReturnKey) {
                $object = $sqlResult->id;
            } else {

                if ($mapper == null) {
                    $object = ObjectUtil::fetchObject($sqlResult);
                } else {

                    $object = MapperUtil::fetchObject($sqlResult, $mapper);
                }
            }
        } catch (Exception $e) {
            throw $e;
        }

        return $object;
    }


    /**
     * Process the mysql result
     *
     * @param MySqlResult $mysqlResult
     * @param bool $isReturnKey
     * @return MySqlResult
     * @throws SQLException
     */
    private function processResult($mysqlResult, $isReturnKey = FALSE)
    {

        if (!$mysqlResult) {
            $sqlException = $this->parseSqlException();
            throw $sqlException;
        }

        $sqlresult = new MySqlResult();
        $sqlresult->sqlResult = $mysqlResult;
        $sqlresult->id = $isReturnKey ? mysqli_insert_id($this->connection) : NULL;

        return $sqlresult;
    }

    /**
     * Parse and create sql exception
     *
     * @return SQLException
     */
    private function parseSqlException()
    {
        $errorMessage = mysqli_error($this->connection);
        switch (mysqli_errno($this->connection)) {
            case 1062:
                $errorCode = SQLException::DUPLICATE_ENTRY;
                break;
            case 1215:
                $errorCode = SQLException::CANNOT_ADD_FOREIGN_KEY;
                break;
            case 1451:
                $errorCode = SQLException::CANNOT_DELETE_OR_UPDATE_ROW_FOREIGN_KEY_FAILED;
                break;

            default:
                // TO DO Add more errors
                $errorCode = mysqli_errno($this->connection);
                break;
        }
        return new SQLException($errorCode, $errorMessage);
    }

    /**
     * Method for executing the mysql query and return result as object list
     *
     * @param string $sql sql string to be executed
     * @param null $mapper
     * @return object[] List
     * @throws Exception
     */
    protected function executeQueryForList($sql, $mapper = NULL)
    {
        $sqlResult = NULL;
        $objectList = [];
        //Establish the connection by invoking establishConnection which is defined in IMySqlConnector
        $this->establishConnection();
        $result = mysqli_query($this->connection, $sql);
        try {
            $sqlResult = $this->processResult($result);
            if (empty($mapper)) {
                $objectList = ObjectUtil::fetchObjectList($sqlResult);
            } else {
                $objectList = MapperUtil::fetchObjectList($sqlResult, $mapper);
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $objectList;
    }

}