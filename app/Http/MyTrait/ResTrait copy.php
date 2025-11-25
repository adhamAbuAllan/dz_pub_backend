<?php
namespace App\Http\MyTrait;

trait ResTrait
{
    protected function success($data)
    {
        return response()->json(
            [
                "status" => true,
                "msg" => "",
                "data" => $data
            ]
        );
    }

    protected function fail($msg)
    {
        return response()->json(
            [
                "status" => false,
                "msg" => $msg,
                "data" => null
            ],
            404
        );
    }


}

?>
