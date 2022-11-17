<?php


namespace com\oscillate\core\mapper;


class CompanyMapper implements IMapper
{
    private $mapper = [];
    private static $_instance = null;

    /**
     * Preventing any outside instantiation of this class
     *
     * CompanyService constructor.
     */
    private function __construct()
    {
    }

    /**
     * Preventing any object or instance of that class to be cloned
     */
    private function __clone()
    {
    }

    /**
     * Have a single globally accessible static method
     * @return CompanyMapper|null
     */
    public static function getInstance()
    {
        if (!is_object(self::$_instance))
            self::$_instance = new self ();
        return self::$_instance;
    }

    const GET_ALL_COMPANY_DETAILS = "GET_ALL_COMPANY_DETAILS";
    const GET_COMPANY_STOCKS = "GET_COMPANY_STOCKS";

    /**
     * @return array|void
     */
    public function getMapper()
    {
        if (empty ($this->mapper)) {
            $this->mapper [self::GET_ALL_COMPANY_DETAILS] = $this->getCompanyDetailsMapper();
            $this->mapper [self::GET_COMPANY_STOCKS] = $this->getCompanyStocksPriceMapper();
        }
        return $this->mapper;
    }

    /**
     * @return ResultMap
     */
    private function getCompanyDetailsMapper()
    {
        $mapper = new ResultMap("getCompanyDetailsMapper", 'com\oscillate\core\dto\Company', 'id', 'id');
        $mapper->results = [];
        $mapper->results [] = new Result('id', 'id');
        $mapper->results [] = new Result('name', 'name');
        return $mapper;
    }

    private function getCompanyStocksPriceMapper()
    {
        $mapper = new ResultMap("getCompanyStocksPriceMapper", 'com\oscillate\core\dto\Company', 'id', 'company_id');
        $mapper->results = [];
        $mapper->results [] = new Result('id', 'company_id', Result::INT);
        $mapper->results [] = new Result('name', 'company_name');
        $mapper->results[] = new Result('stocks', "stocks", Result::OBJECT_ARRAY, $this->getStockPriceMapper());
        return $mapper;
    }

    private function getStockPriceMapper()
    {
        $mapper = new ResultMap("getCompanyStocksPriceMapper", 'com\oscillate\core\dto\StockPrice', 'id', 'stock_id');
        $mapper->results = [];
        $mapper->results [] = new Result('id', 'stock_id', Result::INT);
        $mapper->results [] = new Result('date', 'stock_date');
        $mapper->results[] = new Result('price', "stock_price", Result::DOUBLE);
        return $mapper;
    }

}