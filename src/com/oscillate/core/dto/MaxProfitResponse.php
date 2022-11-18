<?php


namespace com\oscillate\core\dto;


class MaxProfitResponse
{
    /**
     * @var double
     */
    public $maxProfit;

    /**
     * @var Trade[]
     */
    public $tradedStocks;
}