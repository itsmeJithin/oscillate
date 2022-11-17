<?php


namespace com\oscillate\core\request;


class AddNewStockRequest extends BaseRequest
{
    /**
     * @var integer
     */
    public $companyId;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $price;

}