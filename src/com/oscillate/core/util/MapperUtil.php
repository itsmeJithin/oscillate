<?php


namespace com\oscillate\core\util;


use com\oscillate\core\dto\MySqlResult;
use com\oscillate\core\exception\MapperException;
use com\oscillate\core\mapper\Result;
use com\oscillate\core\mapper\ResultMap;

class MapperUtil
{

    /**
     * Method for creating object from result
     *
     * @param MySqlResult $result
     * @param ResultMap $mapper
     * @return $objectList[]
     */
    public static function fetchObject($result, $mapper)
    {
        $mappedObject = NULL;

        if (MySqlUtil::hasRows($result->sqlResult)) {

            $inObjectsArray = [];
            while ($fromObject = MySqlUtil::fetchObject($result->sqlResult)) {

                $mappedObject = self::mapObject($mapper, $fromObject, $inObjectsArray);
                $inObjectsArray[$fromObject->{$mapper->primaryKeyColumn}] = $mappedObject;
            }
        }
        return $mappedObject;
    }

    /**
     * Method for creating object List from result
     *
     * @param MYSQLResult $result
     * @param ResultMap $mapper
     * @return $objectList[]
     */
    public static function fetchObjectList($result, $mapper)
    {
        $mappedObjectList = [];
        $mappedObject = null;

        if (MySqlUtil::hasRows($result->sqlResult)) {

            while ($fromObject = MySqlUtil::fetchObject($result->sqlResult)) {

                $mappedObject = self::mapObject($mapper, $fromObject, $mappedObjectList);
                $mappedObjectList[$fromObject->{$mapper->primaryKeyColumn}] = $mappedObject;
            }
        }

        // MySqlUtil::freeResult($result->sqlResult);
        return array_values($mappedObjectList);
    }

    /**
     * Validate Mapper class
     *
     * @param ResultMap $mapper
     * @throws MapperException
     */
    public static function validateMapper($mapper)
    {
        // If mapper is null throw exception else go ahead with mapping
        if (empty($mapper)) {
            throw new MapperException(MapperException::MAPPER_NOT_DEFINED, "Mapper class not defined!.");
        }

        if (empty($mapper->primaryKeyColumn)) {
            throw new MapperException(MapperException::MAPPER_PRIMARY_KEY_COLUMN_NOT_DEFINED, "Primar Key Column property not set for the mapper '" . $mapper->id . "'");
        }
        if (empty($mapper->primaryKey)) {
            throw new MapperException(MapperException::MAPPER_PRIMARY_KEY_NOT_DEFINED, "Primar Key  property not set for the mapper '" . $mapper->id . "'");
        }
    }

    /**
     * Find Object in an objectArray
     *
     * @param array $objectsArray
     * @param string $key
     * @param int $id
     * @return object
     */
    public static function findObject($objectsArray, $key, $id)
    {
        foreach ($objectsArray as $value) {

            if ($value->{$key} == $id) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Map object from sql result to dto or model classes
     *
     * @param ResultMap $mapper
     * @param object $fromObject
     * @param array $children
     * @return object
     */
    public static function mapObject($mapper, $fromObject, $children)
    {
        $id = null;
        $toObject = null;
        $subObject = null;
        $alreadyMapped = false;
        // Validate Mapper class
        self::validateMapper($mapper);
        // Find the id of the fromobject to check if it is already mapped or not
        $id = $fromObject->{$mapper->primaryKeyColumn};

        // If id is null then no mapping found
        if ($id == null) {
            return null;
        }
        // Find already mapped object else create new object from mapper class
        $toObject = self::findObject($children, $mapper->primaryKey, $fromObject->{$mapper->primaryKeyColumn});
        if ($toObject == null) {
            $toObject = new $mapper->class();
        }

        // Map all mapped result property from mysql result object to dto/model classes
        foreach ($mapper->results as $result) {
            // If result type is null then no relation exist.so directly map the value
            // else check for result type to find about the relationship

            switch ($result->resultType) {
                case Result::INT:
                    $toObject->{$result->property} = (int)$fromObject->{$result->column};
                    break;
                case Result::BOOLEAN:
                    $toObject->{$result->property} = (bool)$fromObject->{$result->column};
                    break;
                case Result::DOUBLE:
                    $toObject->{$result->property} = (double)$fromObject->{$result->column};
                    break;
                case Result::FLOAT:
                    $toObject->{$result->property} = (float)$fromObject->{$result->column};
                    break;
                // One to one relation ship
                case Result::OBJECT:
                    $subObject = self::mapOnetoOne($result->resultMap, $fromObject,$toObject->{$result->property});
                    $toObject->{$result->property} = $subObject;
                    break;
                // One to many relation ship
                case Result::OBJECT_ARRAY:
                    $subObject = null;
                    $alreadyMapped = false;
                    if (empty($toObject->{$result->property})) {
                        $toObject->{$result->property} = [];
                        // map the object
                        $subObject = self::mapObject($result->resultMap, $fromObject, $toObject->{$result->property});
                    } else {

                        $subObject = self::findObject($toObject->{$result->property}, $result->resultMap->primaryKey, $fromObject->{$result->resultMap->primaryKeyColumn});

                        // Check if object already mapped or not
                        // If mapped get the instance and do the mapping else
                        // map the object and add it to the to object list
                        if (empty($subObject)) {
                            $alreadyMapped = false;
                        } else {
                            $alreadyMapped = true;
                        }
                        // map the object
                        $subObject = self::mapObject($result->resultMap, $fromObject, $toObject->{$result->property});
                    }
                    if (! empty($subObject) && ! $alreadyMapped) {
                        $toObject->{$result->property}[] = $subObject;
                    }
                    break;

                // Convert string to array
                case Result::ARRAY_FROM_STRING:

                    $toObject->{$result->property} = explode($result->arrayFromStringSeparator,$fromObject->{$result->column});
                    break;

                // parse json value
                case Result::OBJECT_FROM_JSON:

                    $toObject->{$result->property} = json_decode($fromObject->{$result->column});
                    break;
                default:
                    //If result type is null
                    $toObject->{$result->property} = $fromObject->{$result->column};
                    break;
            }

        }

        return $toObject;
    }

    /**
     * Method fro mapping one to one relation ship
     *
     * @param ResultMap $mapper
     * @param $fromObject
     * @param $toObject
     * @return object
     * @throws MapperException
     */
    public static function mapOnetoOne($mapper, $fromObject,$toObject)
    {
        $id = null;
        //         $toObject = null;
        $subObject = null;
        $alreadyMapped = false;
        // Validate Mapper class
        self::validateMapper($mapper);
        // Find the id of the fromobject
        $id = $fromObject->{$mapper->primaryKeyColumn};

        // If id is null then no mapping found
        if ($id == null) {
            return null;
        }
        $toObject = empty($toObject) ? new $mapper->class() : $toObject;
        foreach ($mapper->results as $result) {


            switch ($result->resultType) {
                case Result::INT:
                    $toObject->{$result->property} = (int)$fromObject->{$result->column};
                    break;
                case Result::BOOLEAN:
                    $toObject->{$result->property} = (bool)$fromObject->{$result->column};
                    break;
                case Result::DOUBLE:
                    $toObject->{$result->property} = (double)$fromObject->{$result->column};
                    break;
                case Result::FLOAT:
                    $toObject->{$result->property} = (float)$fromObject->{$result->column};
                    break;
                // One to one relation ship
                case Result::OBJECT:
                    $toObject->{$result->property} = self::mapOnetoOne($result->resultMap, $fromObject,$toObject->{$result->property});

                    break;
                // One to many releation ship
                case Result::OBJECT_ARRAY:
                    $subObject = null;
                    $alreadyMapped = false;
                    if (empty($toObject->{$result->property})) {
                        $toObject->{$result->property} = [];
                        // map the object
                        $subObject = self::mapObject($result->resultMap, $fromObject, $toObject->{$result->property});
                    } else {

                        $subObject = self::findObject($toObject->{$result->property}, $result->resultMap->primaryKey, $fromObject->{$result->resultMap->primaryKeyColumn});
                        // Check if object already mapped or not
                        // If mapped getthe instance and do the mapping else
                        // map the object and add it to the toobject list
                        if (empty($subObject)) {
                            $alreadyMapped = false;
                        } else {
                            $alreadyMapped = true;
                        }
                        // map the object
                        $subObject = self::mapObject($result->resultMap, $fromObject, $toObject->{$result->property});
                    }

                    if (! empty($subObject) && ! $alreadyMapped) {
                        $toObject->{$result->property}[] = $subObject;
                    }
                    break;

                // Convert string to array
                case Result::ARRAY_FROM_STRING:

                    $toObject->{$result->property} = explode($result->arrayFromStringSeparator,$fromObject->{$result->column});
                    break;

                // parse json value
                case Result::OBJECT_FROM_JSON:

                    $toObject->{$result->property} = json_decode($fromObject->{$result->column});
                    break;
                default:
                    //If result type is null
                    $toObject->{$result->property} = $fromObject->{$result->column};
                    break;
            }



        }
        return $toObject;
    }
}