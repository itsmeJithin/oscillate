<?php


namespace com\oscillate\core\util;


use com\oscillate\core\exception\CoreException;
use phpDocumentor\Reflection\Types\This;

class CommonUtil
{
    /**
     * @param string $date
     * @param string $format
     * @throws CoreException
     */
    public static function formatDate($date, $format = "Y-m-d")
    {
        try {
//            $givenFormat = self::extractDateFormat($date);
            return date($format, strtotime($date));
        } catch (\Exception $e) {
            throw new CoreException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Identifying given date format
     *
     * @param $string
     * @return string|null
     */
    public static function extractDateFormat($string)
    {
        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $string))
            return 'd-m-Y';
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $string))
            return 'Y-m-d';

        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $string))
            return 'm/d/Y';

        if (preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $string))
            return 'Y/m/d';

        if (preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $string))
            return 'd.m.Y';
        return null;
    }
}