<?php

namespace Dipesh79\LogArcLaravel\Service;

use Dipesh79\LogArcLaravel\Exception\ProjectKeyNotFoundException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * LogArcService is responsible for logging messages to an external service.
 * It supports various log levels and formats the data before sending it.
 */
class LogArcService
{
    /**
     * @var string $projectKey The project key used for authentication with the logging service.
     */
    protected string $projectKey;

    /**
     * @var string $endpoint The endpoint URL of the logging service.
     */
    protected string $endpoint;

    /**
     * Constructor initializes the service by retrieving the project key.
     *
     * @throws ProjectKeyNotFoundException If the project key is not found in the configuration.
     */
    public function __construct()
    {
        $this->projectKey = $this->getProjectKey();
        $this->endpoint = config('logarc.endpoint');
    }

    /**
     * Retrieves the project key from the configuration.
     *
     * @return string The project key.
     * @throws ProjectKeyNotFoundException If the project key is not set or empty.
     */
    private function getProjectKey(): string
    {
        $key = config('logarc.project_key');
        if (empty($key)) {
            throw new ProjectKeyNotFoundException();
        }
        return $key;
    }

    /**
     * Logs a debug message.
     *
     * @param string|null $message The message to log.
     * @param array|null $data The data to log.
     * @throws ConnectionException If the HTTP request fails.
     */
    public function debug(string $message = null, array $data = null): void
    {
        $this->log('debug', $message, $data);
    }

    /**
     * Formats and sends the log data to the logging service.
     *
     * @param string|null $message The message to log.
     * @param array|null $data The data to log.
     * @param string $level The log level (e.g., debug, error, info).
     * @throws ConnectionException If the HTTP request fails.
     */
    private function log(string $level, string $message = null, array $data = null): void
    {
        $traceback = debug_backtrace();
        $className = $traceback[3]['class'] ?? 'UnknownClass';
        $methodName = $traceback[3]['function'] ?? 'UnknownMethod';
        $lineNumber = $traceback[2]['line'] ?? 0;

        $this->sendLogRequest([
            'project_key' => $this->projectKey,
            'log_timestamp' => Carbon::now(config('app.timezone'))->format('Y-m-d\TH:i:s\Z'),
            'app_env' => config('app.env'),
            'level' => $level,
            'class_name' => $className,
            'method_name' => $methodName,
            'line_number' => $lineNumber,
            'message' => $message,
            'data' => $data,
            'user' => auth()->check() ? auth()->user()->toArray() : null,
        ]);
    }

    /**
     * Sends the log data to the external logging service.
     *
     * @param array $data The log data to send.
     * @throws ConnectionException If the HTTP request fails.
     */
    private function sendLogRequest(array $data): void
    {
        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post("{$this->endpoint}/log", $data);
        if (!$response->successful()) {
            Log::error('LogArc request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }

    /**
     * Logs an error message.
     *
     * @param string|null $message The message to log.
     * @param array|null $data The data to log.
     * @throws ConnectionException If the HTTP request fails.
     */
    public function error(string $message = null, array $data = null): void
    {
        $this->log('error', $message, $data);
    }

    /**
     * Logs an informational message.
     *
     * @param string|null $message The message to log.
     * @param array|null $data The data to log.
     * @throws ConnectionException If the HTTP request fails.
     */
    public function info(string $message = null, array $data = null): void
    {
        $this->log('info', $message, $data);
    }

    /**
     * Logs a notice message.
     *
     * @param string|null $message The message to log.
     * @param array|null $data The data to log.
     * @throws ConnectionException If the HTTP request fails.
     */
    public function notice(string $message = null, array $data = null): void
    {
        $this->log('notice', $message, $data);
    }

    /**
     * Logs a warning message.
     *
     * @param string|null $message The message to log.
     * @param array|null $data The data to log.
     * @throws ConnectionException If the HTTP request fails.
     */
    public function warning(string $message = null, array $data = null): void
    {
        $this->log('warning', $message, $data);
    }

    /**
     * Logs a critical message.
     *
     * @param string|null $message The message to log.
     * @param array|null $data The data to log.
     * @throws ConnectionException If the HTTP request fails.
     */
    public function critical(string $message = null, array $data = null): void
    {
        $this->log('critical', $message, $data);
    }

    /**
     * Logs an alert message.
     *
     * @param string|null $message The message to log.
     * @param array|null $data The data to log.
     * @throws ConnectionException If the HTTP request fails.
     */
    public function alert(string $message = null, array $data = null): void
    {
        $this->log('alert', $message, $data);
    }

    /**
     * Logs an emergency message.
     *
     * @param string|null $message The message to log.
     * @param array|null $data The data to log.
     * @throws ConnectionException If the HTTP request fails.
     */
    public function emergency(string $message = null, array $data = null): void
    {
        $this->log('emergency', $message, $data);
    }
}
