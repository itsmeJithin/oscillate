<?php


namespace com\oscillate\core\response;


use com\oscillate\core\dto\Company;
use com\oscillate\core\dto\Trade;

class CompanyStockResponse extends BaseResponse
{
    /**
     * @var Company
     */
    public $company;

    /**
     * @var double
     */
    public $maxProfit;

    /**
     * @var Trade[]
     */
    public $tradedStocks;

    /**
     * mean of stocks
     * @var double
     */
    public $mean;

    /**
     * standard deviation of stocks
     * @var double
     */
    public $sd;
}