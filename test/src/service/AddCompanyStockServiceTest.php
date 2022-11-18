<?php


namespace test\service;


use com\oscillate\core\exception\CoreException;
use com\oscillate\core\request\AddNewStockRequest;
use com\oscillate\core\service\AddCompanyStocksService;
use com\oscillate\core\test\BaseTestCase;

class AddCompanyStockServiceTest extends BaseTestCase
{
    /**
     * @return AddCompanyStocksService|null
     */
    public function testInstanceForServiceCanBeAcquired()
    {
        $addCompanyStockService = AddCompanyStocksService::getInstance();
        $this->assertInstanceOf('com\\oscillate\\core\\service\\AddCompanyStocksService', $addCompanyStockService, "Cannot Get Instance For AddCompanyStocksService");
        return $addCompanyStockService;
    }

    /**
     * @depends testInstanceForServiceCanBeAcquired
     * @param AddCompanyStocksService $addCompanyStockService
     * @throws CoreException
     */
    public function testSuccessAddCompanyStocks($addCompanyStockService)
    {
        $request = new AddNewStockRequest();
        $request->companyId = 1;
        $request->date = "2022-11-15";
        $request->price = "200";
        $stockId = $addCompanyStockService->addNewCompanyStock($request);
        $this->assertNotEmpty($stockId);
        $this->assertGreaterThan(0, $stockId);
        $this->deleteElement("stock_prices", "id", $stockId);
    }

    /**
     * @depends testInstanceForServiceCanBeAcquired
     * @param AddCompanyStocksService $addCompanyStockService
     * @throws CoreException
     */
    public function testFailureAddCompanyStocks($addCompanyStockService)
    {
        $this->expectException("com\oscillate\core\\exception\CoreException");
        $this->expectExceptionCode(1064);
        $request = new AddNewStockRequest();
        $request->companyId = 1021;
        $request->date = "2022-11-15";
        $stockId = $addCompanyStockService->addNewCompanyStock($request);
    }

}