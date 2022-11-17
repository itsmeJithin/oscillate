<?php


namespace com\oscillate\core\connection;

/**
 * @author Jithin Vijayan
 * Interface IMySQLConnector
 * @package com\oscillate\core\connection
 */
interface IMySQLConnector
{
    public function establishConnection();
}