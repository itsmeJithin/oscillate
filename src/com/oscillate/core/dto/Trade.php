<?php


namespace com\oscillate\core\dto;


class Trade
{
    /**
     * @var string
     */
    public $boughtOn;
    /**
     * @var double
     */
    public $boughtPrice=0;

    /**
     * @var integer
     */
    public $numberOfStocks;

    /**
     * @var double
     */
    public $soldPrice=0;

    /**
     * @var string
     */
    public $soldOn;
}