<?php

use com\oscillate\core\request\GetCompanyStockRequest;
use com\oscillate\core\service\CompanyService;
use com\oscillate\core\util\ResultHandler;

require_once '../../../../vendor/autoload.php';
$action = $_REQUEST['action'];
switch ($action) {
    case "get-basic-details":
        try {
            $companies = CompanyService::getInstance()->getAllCompanies();
            exit(ResultHandler::success("Details fetched successfully", $companies));
        } catch (\Exception $e) {
            exit(ResultHandler::failed($e->getMessage(), $e->getCode()));
        }
    case "track-data":
        try {
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
            $request = new GetCompanyStockRequest();
            $request->endDate = $endDate;
            $request->startDate = $startDate;
            $request->companyId = $_POST['companyId'];
            $response = CompanyService::getInstance()->getCompanyStocks($request);
            exit(ResultHandler::success("Details fetched successfully", $response));
        } catch (\Exception $e) {
            exit(ResultHandler::failed($e->getMessage(), $e->getCode()));
        }
}