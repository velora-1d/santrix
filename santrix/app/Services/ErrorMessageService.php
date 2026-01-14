<?php

namespace App\Services;

use Illuminate\Database\QueryException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ErrorMessageService
{
    /**
     * Sanitize exception message for production environment
     * Prevents leaking sensitive information (SQL schema, file paths, etc.)
     */
    public static function sanitize(\Throwable $e): string
    {
        // Development: show full details for debugging
        if (app()->environment('local', 'development')) {
            return $e->getMessage();
        }

        // Production: map to user-friendly, safe messages
        return match (get_class($e)) {
            QueryException::class => 'Terjadi kesalahan database. Silakan coba lagi atau hubungi administrator.',
            RequestException::class => 'Layanan eksternal sedang gangguan. Silakan coba lagi dalam beberapa menit.',
            ValidationException::class => $e->getMessage(), // Validation errors are safe to show
            \Illuminate\Database\Eloquent\ModelNotFoundException::class => 'Data yang Anda cari tidak ditemukan.',
            \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class => 'Halaman tidak ditemukan.',
            \Illuminate\Auth\AuthenticationException::class => 'Sesi Anda telah berakhir. Silakan login kembali.',
            default => 'Terjadi kesalahan sistem. Tim teknis telah diberitahu. Silakan hubungi administrator jika masalah berlanjut.',
        };
    }

    /**
     * Log error with full context for debugging, then return sanitized message
     * 
     * @param \Throwable $e The exception to log and sanitize
     * @param string $context Description of where the error occurred (e.g., 'RegisterTenantController@register')
     * @return string Sanitized, user-safe error message
     */
    public static function logAndSanitize(\Throwable $e, string $context = ''): string
    {
        // Log full error details for debugging
        Log::error("Error in {$context}: " . $e->getMessage(), [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        // Return sanitized message for user
        return self::sanitize($e);
    }
}
