<?php
namespace KejaksaanDev\PortalLogger;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PortalLogger
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $message = [];

    /**
     * @var string
     */
    protected $level;

    const LEVEL_INFO = 'info';
    const LEVEL_DEBUG = 'debug';
    const LEVEL_ERROR = 'error';

    /**
     * PortalLogger constructor.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setMessage(array $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public static function log(array $message, string $level = 'info'): self
    {
        return app('portal-logger')
                ->setMessage($message)
                ->setLevel($level);
    }

    public function contentBody()
    {
        $this->message['level'] = $this->level;

        return $this->message;
    }

    /**
     * Dispatch a log message.
     *
     * @return void
     */
    public function send()
    {
        try {
            $path = config('portal-logger.logger.path');
            $payload = [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($this->contentBody())
            ];

            $promise = $this->client->requestAsync('POST', $path, $payload);
            $promise->wait();
        }
        catch (\Exception $ex) {
            Log::info("Failed send request to logger server : {$ex->getMessage()}");
        }
    }
}