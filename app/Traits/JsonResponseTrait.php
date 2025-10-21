<?php

namespace App\Traits;

use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Throwable;

trait JsonResponseTrait
{
    protected function responseJson(bool $success, string $message, $data = null, int $status = 200)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function handleException(Throwable $e)
    {
        Log::error('PublicRegister error', [
            'type' => class_basename($e),
            'msg'  => $e->getMessage(),
            'file' => $e->getFile().':'.$e->getLine(),
        ]);

        if ($e instanceof ValidationException) {
            return $this->responseJson(false, 'Données invalides', $e->errors(), 422);
        }

        if ($e instanceof QueryException) {
            return $this->responseJson(false, 'Erreur base de données : '.$e->getMessage(), null, 500);
        }

        return $this->responseJson(false, 'Une erreur inattendue est survenue : '.$e->getMessage(), null, 500);
    }
}
