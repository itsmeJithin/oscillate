<?php

use com\oscillate\core\request\AddNewStockRequest;
use com\oscillate\core\request\CreateCompanyRequest;
use com\oscillate\core\service\AddCompanyStocksService;
use com\oscillate\core\service\CompanyService;
use com\oscillate\core\util\CommonUtil;
use com\oscillate\core\util\ResultHandler;

require_once '../../../../vendor/autoload.php';
if (isset($_POST['import'])) {
    $info = pathinfo($_FILES['file']['name']);

    if ($info['extension'] != 'csv') {
        exit(ResultHandler::success("Invalid file uploaded.", "INVALID_FILE"));
    }
    $fileName = $_FILES['file']['tmp_name'];
    try {
        if ($_FILES['file']['size'] > 0) {
            $file = fopen($fileName, "r");
            $flag = true;
            while ($getData = fgetcsv($file, 10000, ",")) {
                if ($flag) {
                    $flag = false;
                    continue;
                }
                $companyName = $getData[2];
                $request = new CreateCompanyRequest();
                $request->name = $companyName;
                $companyId = CompanyService::getInstance()->createCompany($request);
                if ($companyId) {
                    $date = CommonUtil::formatDate($getData[1]);
                    $addRequest = new AddNewStockRequest();
                    $addRequest->companyId = $companyId;
                    $addRequest->price = $getData[3];
                    $addRequest->date = $date;
                    AddCompanyStocksService::getInstance()->addNewCompanyStock($addRequest);
                }
            }
        } else {
            exit(ResultHandler::failed("Please upload a valid file", "INVALID_FILE"));
        }
        exit(ResultHandler::success("Details uploaded successfully"));
    } catch (\Exception $e) {
        exit(ResultHandler::failed($e->getMessage(), $e->getCode()));
    }
} else {
    exit(ResultHandler::success("Invalid request sent", "INVALID_REQUEST"));
}