<?php


namespace com\oscillate\core\service;


use com\oscillate\core\request\AddNewStockRequest;
use com\oscillate\core\exception\CoreException;

class AddCompanyStocksService extends BaseService
{
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
     * @return AddCompanyStocksService|null
     */
    public static function getInstance()
    {
        if (!is_object(self::$_instance))
            self::$_instance = new self ();
        return self::$_instance;
    }

    /**
     * @param AddNewStockRequest $request
     * @return int|Object
     * @throws CoreException
     */
    public function addNewCompanyStock(AddNewStockRequest $request)
    {
        $request = $this->realEscapeObject($request);
        try {
            $sql = "INSERT INTO stock_prices (company_id, price, date, created_at, updated_at) 
                    VALUES ($request->companyId,$request->price,'$request->date',UTC_TIMESTAMP(),UTC_TIMESTAMP())";
            return $this->executeQueryForObject($sql, true);
        } catch (\Exception $e) {
            throw new CoreException($e->getMessage(), $e->getCode());
        }
    }

}