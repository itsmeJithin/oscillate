<?php

namespace test\service;

use com\oscillate\core\exception\CoreException;
use com\oscillate\core\request\CreateCompanyRequest;
use com\oscillate\core\service\CompanyService;
use com\oscillate\core\test\BaseTestCase;

class CompanyServiceTest extends BaseTestCase
{
    /**
     * @return CompanyService|null
     */
    public function testInstanceForServiceCanBeAcquired()
    {
        $companyService = CompanyService::getInstance();
        $this->assertInstanceOf('com\\oscillate\\core\\service\\CompanyService', $companyService, "Cannot Get Instance For CompanyService");
        return $companyService;
    }

    /**
     * @depends testInstanceForServiceCanBeAcquired
     * @param CompanyService $companyService
     * @throws CoreException
     */
    public function testSuccessCreateCompany($companyService)
    {
        $request = new CreateCompanyRequest();
        $request->name = "IBS";
        $companyId = $companyService->createCompany($request);
        $this->assertNotEmpty($companyId);
        $this->assertGreaterThan(0, $companyId);
    }

    /**
     * @depends testInstanceForServiceCanBeAcquired
     * @param CompanyService $companyService
     * @throws CoreException
     */
    public function testFailureCreateCompany($companyService)
    {
        $this->expectException("com\oscillate\core\\exception\CoreException");
        $this->expectExceptionCode("INVALID_COMPANY_NAME");
        $request = new CreateCompanyRequest();
        $companyService->createCompany($request);
    }
}