<?php


namespace com\oscillate\core\request;


class StockRequest extends BaseRequest
{
    /**
     * @var integer
     */
    public $companyName;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $price;

}