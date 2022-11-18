<?php


namespace com\oscillate\core\service;


use com\oscillate\core\dto\Company;
use com\oscillate\core\dto\MaxProfitResponse;
use com\oscillate\core\dto\StockPrice;
use com\oscillate\core\dto\Trade;
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
     * creating company if company not exist in the table
     *
     * @param CreateCompanyRequest $request
     * @return Object|integer
     * @throws CoreException
     */
    public function createCompany(CreateCompanyRequest $request)
    {
        $request = $this->realEscapeObject($request);
        try {
            if (empty($request->name)) {
                throw new CoreException("Invalid company name given", CoreException::INVALID_COMPANY_NAME);
            }
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
     * internal function to get company id from name
     *
     * @param $name
     * @return integer
     * @throws CoreException
     */
    private function getCompanyIdByName($name)
    {
        $sql = "SELECT id FROM companies WHERE name ='$name'";
        try {
            return $this->executeQueryForObject($sql)->id;
        } catch (\Exception $e) {
            throw new CoreException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * updating company details
     *
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
                    WHERE c.id =$request->companyId AND sp.date BETWEEN '$request->startDate' AND '$request->endDate'
                    ORDER BY sp.date DESC";
            $response->company = $this->executeQueryForObject($sql, false, $this->mapper[CompanyMapper::GET_COMPANY_STOCKS]);
            $meanAndSd = $this->findSDAndMean($response->company->stocks);
            $response->mean = $meanAndSd->mean;
            $response->sd = $meanAndSd->sd;
            if (!$response->company || count($response->company->stocks) === 1) {
                $response->maxProfit = 0;
                $response->tradedStocks = [];
                return $response;
            }
            $tradeResponse = $this->findMaxProfit(0, count($response->company->stocks) - 1, $response->company->stocks);
            $response->maxProfit = $tradeResponse->maxProfit;
            $response->tradedStocks = $tradeResponse->tradedStocks;
        } catch (\Exception $e) {
            throw new CoreException($e->getMessage(), $e->getCode());
        }
        return $response;
    }

    /**
     * calculating the maximum profit by finding the best day to buy and sell stocks
     *
     * @param integer $from
     * @param integer $to
     * @param StockPrice[] $stocks
     * @return MaxProfitResponse
     * @throws CoreException
     */
    private function findMaxProfit($from, $to, $stocks)
    {
        $stocks = array_reverse($stocks);
        $response = new MaxProfitResponse();
        $tradeStocks = [];
        $i = $from;
        try {
            while ($i <= $to) {
                /**
                 * Step 1: Finding the minimum stock price
                 */
                while (($i < $to) && ($stocks[$i + 1]->price <= $stocks[$i]->price))
                    $i++;
                /**
                 * Step 2: If minimum stock price is at the end of array we can skip the profit calculation
                 */
                if ($i == $to) {
                    $response->maxProfit = 0;
                    $response->tradedStocks = [];
                    break;
                }
                /**
                 * Step 3: Considering that i(th) stock as purchased
                 */
                $trade = new Trade();
                $trade->boughtPrice = $stocks[$i]->price;
                $trade->boughtOn = $stocks[$i]->date;
                $trade->numberOfStocks = 200;
                /**
                 * Step 4: Incrementing the value of i
                 */
                $i++;
                /**
                 * Step 5: Checking i is less than size of the array and checking price of i(th) stock is less than (i-1)th stock
                 * and finding the maximum price
                 */
                while (($i < $to + 1) && ($stocks[$i]->price >= $stocks[$i - 1]->price))
                    $i++;
                /**
                 * Step 6: Maximum price of the stock will be in (i-1)th position and considering it as sold
                 */
                $trade->soldPrice = $stocks[$i - 1]->price;
                $trade->soldOn = $stocks[$i - 1]->date;
                $tradeStocks[] = $trade;
            }
            if (count($tradeStocks) > 0) {
                $maxProfit = 0;
                foreach ($tradeStocks as $stock) {
                    $maxProfit += ($stock->soldPrice * $stock->numberOfStocks) - ($stock->boughtPrice * $stock->numberOfStocks);
                }

                $response->maxProfit = $maxProfit;
                $response->tradedStocks = $tradeStocks;
            }

            return $response;
        } catch (\Exception $e) {
            throw new CoreException($e->getMessage(), $e->getMessage());
        }
    }

    /**
     * @param StockPrice[] $stocks
     */
    private function findSDAndMean($stocks)
    {
        $totalPrice = 0;
        $sd = 0;
        $response = new \stdClass();
        for ($i = 0; $i < count($stocks); $i++) {
            $totalPrice += $stocks[$i]->price;
        }
        $mean = $totalPrice / count($stocks);
        for ($i = 0; $i < count($stocks); $i++) {
            $sd += pow($stocks[$i]->price - $mean, 2);
        }
        $response->sd = round(sqrt($sd / count($stocks)), 2);
        $response->mean = round($mean, 2);
        return $response;

    }
}