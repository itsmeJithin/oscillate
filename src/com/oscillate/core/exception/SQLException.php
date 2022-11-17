<?php


namespace com\oscillate\core\exception;


class SQLException extends CoreException
{
    const DUPLICATE_ENTRY="DUPLICATE_ENTRY";
    const CANNOT_ADD_FOREIGN_KEY="CANNOT_ADD_FOREIGN_KEY";
    const CANNOT_DELETE_OR_UPDATE_ROW_FOREIGN_KEY_FAILED="CANNOT_DELETE_OR_UPDATE_ROW_FOREIGN_KEY_FAILED";
}