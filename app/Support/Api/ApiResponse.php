<?php

namespace App\Support\Api;

use Illuminate\Support\Facades\DB;

/**
 * @version 1.0
 */
trait ApiResponse
{
    protected int $code = 200;

    protected int $customCode = 2000;

    protected array $body = [];

    protected array $routes = [];

    protected ?string $message = null;

    protected string $info = 'from response action';


    public function successResponse($data = null, $message = 'success', $statusCode = 200, $success = true)
{
    $response = [
        'success' => $success,
        'message' => $message,
    ];
    if ($data !== null) {
        // Use json_encode with JSON_INVALID_UTF8_IGNORE to skip bad UTF-8 characters
        $jsonData = json_encode($data, JSON_INVALID_UTF8_IGNORE);
        if ($jsonData === false) {
            $response['data'] = null; // fallback
        } else {
            $response['data'] = json_decode($jsonData, true);
        }
    }

    return response()->json($response, $statusCode);
}

    public function errorResponse($responseCode, $message = 'Bad request', $statusCode = 400, $success = false, $data = null)
    {
        $response = [
            'success' => $success,
            'response_code' => $responseCode,
            'message' => $message,
        ];
        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    protected function apiResponse(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'custom_code' => $this->customCode,
            'status' => $this->code === 200,
            'message' => $this->message ?? __('app.messages.data_retrieved_successfully'),
            'body' => (object) $this->body,
            'info' => $this->info,
            'db' => DB::getDatabaseName(),
        ], $this->code);
    }

    protected function apiBody(array|object $body = []): static
    {
        foreach ($body as $key => $value) {
            $this->body[$key] = $value;
        }

        return $this;
    }

    protected function apiMessage(string $message = ''): static
    {
        $this->message = $message;

        return $this;
    }

    protected function apiInfo(string $info = '', $addToCurrent = false): static
    {
        $addToCurrent ? $this->info .= $info : $this->info = $info;

        return $this;
    }

    protected function apiCode(int $code): static
    {
        $this->code = $code;

        return $this;
    }

    protected function apiCustomCode(int $customCode): static
    {
        $this->customCode = $customCode;

        return $this;
    }

    /**
     * @deprecated
     */
    protected function strings(): array
    {
        return [

        ];
    }
}
