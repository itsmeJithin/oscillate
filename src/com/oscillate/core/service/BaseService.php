<?php

namespace com\oscillate\core\service;


use com\oscillate\core\connection\MySqlConnector;
use com\oscillate\core\connection\MySQLQuery;
use com\oscillate\core\exception\CoreException;

/**
 * @author Jithin Vijayan
 * Class BaseService
 * @package com\oscillate\core\service
 */
class BaseService extends MySQLQuery
{
    public $mySqlConnector;

    /**
     * @throws CoreException
     */
    public function establishConnection()
    {
        $this->mySqlConnector = MySqlConnector::getInstance();

        if ($this->mySqlConnector->instanceOf != null && !$this->mySqlConnector->instanceOf instanceof BaseService) {
            $this->mySqlConnector->closeConnection();
        }
        if (!$this->mySqlConnector->connection) {

            $this->setDBConnectionProperties();
        }
        $this->connection = $this->mySqlConnector->getConnection();
    }

    protected function setDBConnectionProperties()
    {
        $this->mySqlConnector->instanceOf = $this;
        $this->mySqlConnector->DB_HOST = "localhost";
        $this->mySqlConnector->DB_USER = "root";
        $this->mySqlConnector->DB_PASSWORD = "root@mac";
        $this->mySqlConnector->DB_NAME = "oscillate";
    }

}