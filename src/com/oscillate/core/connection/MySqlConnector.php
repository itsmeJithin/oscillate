<?php


namespace com\oscillate\core\connection;


use com\oscillate\core\exception\CoreException;
use com\oscillate\core\util\MySqlUtil;

/**
 * @author Jithin Vijayan
 * Class MySqlConnector
 * @package com\oscillate\core\connection
 */
class MySqlConnector
{

    public $instanceOf;

    public $DB_HOST;

    public $DB_USER;

    public $DB_PASSWORD;

    public $DB_NAME;

    private static $_instance = null;

    public $connection = null;

    //Locking down constructor
    private function __construct()
    {
    }

    // prevent cloning object of this class
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    //Have a single globally accessible static method
    public static function getInstance()
    {
        if (!is_object(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @throws CoreException
     */
    public function getConnection()
    {
        if ($this->connection == NULL) {
            if (empty($this->DB_PASSWORD) || empty($this->DB_USER) || empty($this->DB_PASSWORD) || empty($this->DB_NAME)) {
                throw new CoreException(CoreException::DB_CONFIG_NOT_SET, "DB configuration connection variables not set properly");
            }
            $this->connection = new \mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASSWORD, $this->DB_NAME);
        }
        if ($this->connection->connect_error) {

            $this->connection = NULL;
            throw new CoreException(CoreException::CONNECTION_FAILED, $this->connection->connect_error);
        }
        return $this->connection;
    }
    public function closeConnection()
    {
        // close connection if exist
        if ($this->connection) {
            MySqlUtil::closeConnection($this->connection);
        }

        $this->connection = NULL;
    }
    public function __destruct()
    {
        $this->closeConnection();
    }

    public function destroy()
    {
        $this->closeConnection();
        self::$_instance = null;

    }
}