<?php


namespace com\oscillate\core\dto;


class Company extends BaseDTO
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var StockPrice[]
     */
    public $stocks = [];

}