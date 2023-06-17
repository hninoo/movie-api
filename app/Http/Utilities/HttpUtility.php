<?php
namespace App\Http\Utilities;

class HttpUtility
{
    public function jsonResponse($data, $statusCode, $message)
    {
        return response()->json([
            'result' => $data,
            'statusCode' => $statusCode,
            'message'=> $message
        ], $statusCode);
    }
    public function successResponse($data = null, $message = null)
    {
        return $this->jsonResponse($data, config('http_status.success') , $message ?? 'Success');
    }
    public function createResponse($data = null, $message = null)
    {
        return $this->jsonResponse($data, config('http_status.created'), $message ?? 'Created Succefully');
    }

    public function updateResponse($data = null, $message = null)
    {
        return $this->jsonResponse($data, config('http_status.success'), $message ?? 'Updated Successfully');
    }

    public function deleteResponse($data = null, $message = null)
    {
        return $this->jsonResponse($data, config('http_status.success'), $message ?? 'Deleted Successfully');
    }
}
