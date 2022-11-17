<?php


namespace com\oscillate\core\service;


use com\oscillate\core\dto\Company;
use com\oscillate\core\exception\CoreException;
use com\oscillate\core\mapper\CompanyMapper;
use com\oscillate\core\request\CreateCompanyRequest;
use com\oscillate\core\request\GetCompanyStockRequest;
use com\oscillate\core\request\UpdateCompanyRequest;
use com\oscillate\core\response\CompanyStockResponse;

class CompanyService extends BaseService
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
        $this->mapper = CompanyMapper::getInstance()->getMapper();
    }

    /**
     * Preventing any object or instance of that class to be cloned
     */
    private function __clone()
    {
    }

    /**
     * Have a single globally accessible static method
     * @return CompanyService|null
     */
    public static function getInstance()
    {
        if (!is_object(self::$_instance))
            self::$_instance = new self ();
        return self::$_instance;
    }

    /**
     * @param CreateCompanyRequest $request
     * @return Object|integer
     * @throws CoreException
     */
    public function createCompany(CreateCompanyRequest $request)
    {
        $request = $this->realEscapeObject($request);
        try {
            $companyId = $this->getCompanyIdByName($request->name);
            if ($companyId)
                return $companyId;
            $sql = "INSERT INTO companies (name, created_at, updated_at) 
                    VALUES ('$request->name',UTC_TIMESTAMP(),UTC_TIMESTAMP)";
            return $this->executeQueryForObject($sql, true);
        } catch (\Exception $e) {
            throw new CoreException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $name
     * @return integer
     * @throws CoreException
     */
    public function getCompanyIdByName($name)
    {
        $sql = "SELECT id FROM companies WHERE name ='$name'";
        try {
            return $this->executeQueryForObject($sql)->id;
        } catch (\Exception $e) {
            throw new CoreException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param UpdateCompanyRequest $request
     * @throws CoreException
     */
    public function updateCompany(UpdateCompanyRequest $request)
    {
        $request = $this->realEscapeObject($request);
        try {
            $sql = "UPDATE companies SET name ='$request->name', updated_at=UTC_TIMESTAMP() 
                    WHERE id =$request->id";
            $this->executeQuery($sql);
        } catch (\Exception $e) {
            throw new CoreException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * returns all the available company details
     *
     * @return object[]|Company[]
     * @throws CoreException
     */
    public function getAllCompanies()
    {
        $sql = "SELECT * FROM companies";
        try {
            return $this->executeQueryForList($sql, $this->mapper[CompanyMapper::GET_ALL_COMPANY_DETAILS]);
        } catch (\Exception $e) {
            throw new CoreException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * returns the corresponding stock records between given date ranges of given company
     *
     * @param GetCompanyStockRequest $request
     * @return CompanyStockResponse
     * @throws CoreException
     */
    public function getCompanyStocks(GetCompanyStockRequest $request)
    {
        $request = $this->realEscapeObject($request);
        $response = new CompanyStockResponse();
        try {
            if (empty($request->companyId))
                throw new CoreException("Invalid company details is given", CoreException::INVALID_COMPANY_ID);
            if (empty($request->endDate) || empty($request->startDate) || strtotime($request->startDate) > strtotime($request->endDate))
                throw new CoreException("Invalid date range is given", CoreException::INVALID_DATE_RANGE);

            $sql = "SELECT c.id as company_id,c.name as company_name,
                    sp.id as stock_id,sp.date as stock_date,sp.price as stock_price
                    FROM companies c 
                    INNER JOIN stock_prices sp on c.id = sp.company_id
                    WHERE c.id =$request->companyId
                    ORDER BY sp.date DESC";
            $response->company = $this->executeQueryForObject($sql, false, $this->mapper[CompanyMapper::GET_COMPANY_STOCKS]);
        } catch (\Exception $e) {
            throw new CoreException($e->getMessage(), $e->getCode());
        }
        return $response;
    }

}