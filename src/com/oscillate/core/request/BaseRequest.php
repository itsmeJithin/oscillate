<?php


namespace com\oscillate\core\request;


class BaseRequest
{
    /**
     * @var string
     */
    public $createdDate;

    /**
     * @var string
     */
    public $updatedDate;

    /**
     * @var integer
     */
    public $startIndex=0;

    /**
     * @var int
     */
    public $totalRecords =0;

    /**
     * @var int
     */
    public $numberOfRecordsPerPage =50;
}