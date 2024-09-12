<?php

namespace App\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * Trait Responses.
 */
trait Responses
{
    public function successResponseWithData(mixed $data, int $code = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response()->json(
            data: ['success' => true, 'data' => $data],
            status: $code,
            headers: ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            options: \JSON_UNESCAPED_UNICODE
        );
    }

    public function successResponse(int $code = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response()->json(
            data: ['success' => true],
            status: $code,
            headers: ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            options: \JSON_UNESCAPED_UNICODE
        );
    }

    public function exceptionResponse(\Exception $exception, int $code = 400): void
    {
        $response = response()->json(
            data: [
                'errors' => [
                    'status' => (null == $exception->getCode()) ? 400 : $exception->getCode(),
                    'detail' => $exception->getMessage(),
                ],
            ],
            status: $code,
            headers: ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            options: \JSON_UNESCAPED_UNICODE
        );
        throw new HttpResponseException($response);
    }
}
