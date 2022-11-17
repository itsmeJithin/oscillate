<?php


namespace com\oscillate\core\exception;


class MapperException extends CoreException
{
    const MAPPER_NOT_DEFINED = "MAPPER_NOT_DEFINED";
    const MAPPER_PRIMARY_KEY_NOT_DEFINED = "MAPPER_PRIMARY_KEY_NOT_DEFINED";
    const MAPPER_PRIMARY_KEY_COLUMN_NOT_DEFINED = "MAPPER_PRIMARY_KEY_COLUMN_NOT_DEFINED";
}