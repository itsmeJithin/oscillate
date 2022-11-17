<?php


namespace com\oscillate\core\dto;


class BaseDTO
{
    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var string
     */
    public $updatedAt;

    /**
     * for pagination
     * @var integer
     */
    public $startIndex;

    /**
     * for pagination
     * @var int
     */
    public $requiredCount=50;
}