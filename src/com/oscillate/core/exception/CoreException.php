<?php


namespace com\oscillate\core\exception;

/**
 * @author Jithin Vijayan
 * Class CoreException
 * @package com\oscillate\core\exception
 */
class CoreException extends \Exception
{
    protected $code;
    protected $data;
    protected $message;

    public function __construct($message = "", $code = 0, $data = null)
    {
        $this->message = $message;
        $this->code = $code;
        $this->data = $data;
        $this->logError();
    }

    private function logError()
    {
        $errorMsg = "Error Code : '" . $this->code . "' . Error Message : " . $this->message . " Error Trace : " . $this->getTraceAsString();
        error_log($errorMsg);
    }

    public function getData()
    {
        return $this->data;
    }

    const DB_CONFIG_NOT_SET = "DB_CONFIG_NOT_SET";
    const CONNECTION_FAILED = "CONNECTION_FAILED";
    const INVALID_COMPANY_ID = "INVALID_COMPANY_ID";
    const INVALID_DATE_RANGE = "INVALID_DATE_RANGE";

}