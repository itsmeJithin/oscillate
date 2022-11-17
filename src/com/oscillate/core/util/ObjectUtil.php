<?php


namespace com\oscillate\core\util;


use com\oscillate\core\dto\MySqlResult;
use com\oscillate\core\mapper\ResultMap;

class ObjectUtil
{
/**
* Method for creating object list from result
*
* @param MYSQLResult $result
* @return $objectList[]
*/
    public static function fetchObjectList($result)
    {
        $objectList = [];

        if (MySqlUtil::hasRows($result->sqlResult)) {
            while ($obj = MySqlUtil::fetchObject($result->sqlResult)) {
                $objectList[] = $obj;
            }
        }
        // MySqlUtil::freeResult($result->sqlResult);
        return $objectList;
    }

    /**
     * Method for creating object from result
     *
     * @param MYSQLResult $result
     * @param ResultMap $mapper
     * @return $objectList[]
     */
    public static function fetchObject($result, $mapper = null)
    {
        $object = NULL;

        if (MySqlUtil::hasRows($result->sqlResult)) {

            $object = MySqlUtil::fetchObject($result->sqlResult);
        }
        // MySqlUtil::freeResult($result->sqlResult);
        return $object;
    }
}