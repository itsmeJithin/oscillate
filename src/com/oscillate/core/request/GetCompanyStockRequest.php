<?php


namespace com\oscillate\core\request;


class GetCompanyStockRequest extends BaseRequest
{
    /**
     * @var string
     */
    public $startDate;

    /**
     * @var string
     */
    public $endDate;

    /**
     * @var integer
     */
    public $companyId;

}