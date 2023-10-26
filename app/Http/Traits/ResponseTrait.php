<?php

namespace App\Http\Traits;

use Exception;

trait ResponseTrait
{
    /**
     * Handle Success Cases and responses
     *
     * @param  int  $statusCode
     * @return array<string, mixed>
     */
    public function success($statusCode, $data, $code = 1, $hint = ''): array
    {
        switch ($statusCode) {
            case 200:
                $code = 1020;
                $hint = 'Processed Successfully';
                break;
            case 201:
                $code = 1021;
                $hint = 'Resource created successfully';
                break;
            default:
                $code = $code;
                $hint = $hint;
                break;
        }

        $this->result['status_code'] = $statusCode;
        $this->result['code'] = $code;
        $this->result['hint'] = $hint;
        $this->result['success'] = true;
        $this->result['data'] = $data;

        return $this->result;
    }

    /**
     * Handle Failed Cases and Responses
     *
     * @param  int  $statusCode
     * @return array<string, mixed>
     */
    public function failed($statusCode, $errors, $code = 0, $hint = ''): array
    {
        switch ($statusCode) {
            case 401:
                $code = 1041;
                $hint = 'Unauthenticated';
                break;
            case 403:
                $code = 1043;
                $hint = 'Forbidden';
                break;
            case 404:
                $code = 1044;
                $hint = 'Resource not found';
                break;
            case 409:
                $code = 1049;
                $hint = 'Method Not Allowed';
                break;
            case 422:
                $code = 1422;
                $hint = 'Unprocessable Entity';
                break;
            case 500:
                $code = 1050;
                $hint = 'Server error';
                break;
            default:
                $code = $code;
                $hint = $hint;
                break;
        }

        $this->result['status_code'] = $statusCode;
        $this->result['code'] = $code;
        $this->result['hint'] = $hint;
        $this->result['success'] = false;
        $this->result['errors'] = $errors;

        return $this->result;
    }

    /**
     * Handle Failed Cases and Responses
     *
     * @param Exception $e
     * @return array<string, mixed>
     */
    public function failedWithException(Exception $e): array
    {
        return $this->failed(500, [
            'error' => __('common.something_went_wrong'),
            'description' => [
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
            ],
        ]);
    }
}
