<?php


namespace com\oscillate\core\util;


class ResultHandler
{
    /**
     * Method for sending success response
     *
     * @param mixed $data
     * @param string $msg
     */
    public static function success($msg = null, $data = "")
    {
        try {
            return json_encode(array(
                'success' => true,
                'message' => $msg,
                'data' => $data,
                'errorCode' => NULL
            ));
        }catch (\Exception $e){
            $a=$e;
        }

    }

    /**
     * Method for sending fault response
     *
     * @param string $msg
     * @param string $errorCode
     * @param string $data
     */
    public static function failed($msg = "", $data = null, $errorCode = "")
    {
        return json_encode(array(
            'success' => false,
            'message' => $msg,
            'data' => $data,
            'errorCode' => $errorCode
        ));
    }

}