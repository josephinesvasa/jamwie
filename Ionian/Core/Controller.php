<?php
namespace Ionian\Core;

use Ionian\Errors\ErrorHandlerInterface;

abstract class Controller{
    protected $errorHandler;

    function __construct(ErrorHandlerInterface $errorHandler){
        $this->errorHandler = $errorHandler;
    }

    public function outputJSON($response = "NO MSG PROVIDED!", $data = null){
        header('Content-Type: application/json');
        $dump = ["code" => 200, "response" => $response];
        if(!is_null($data))
            $dump["data"] = $data;

        print_r(json_encode($dump, JSON_PRETTY_PRINT));
    }

    public function getDate(){
        $dateToday=date("Y-m-d");
        return $dateToday;
    }
    public function getTime(){
        $timeToday=date("h:i:s");
        return $timeToday;
    }
}