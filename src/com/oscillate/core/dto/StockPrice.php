<?php


namespace com\oscillate\core\dto;


class StockPrice extends BaseDTO
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $date;

    /**
     * @var double
     */
    public $price;

}