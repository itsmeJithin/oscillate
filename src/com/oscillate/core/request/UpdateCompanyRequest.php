<?php


namespace com\oscillate\core\request;


class UpdateCompanyRequest extends BaseRequest
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

}