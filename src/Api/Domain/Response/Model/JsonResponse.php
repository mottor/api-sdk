<?php

namespace Mottor\Api\Domain\Response\Model;

use Exception;
use Mottor\Api\Domain\Common\Behavior\ConvertStreamTrait;
use Mottor\Api\Domain\Common\Behavior\ConvertJsonTrait;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\InjectContentTypeTrait;

class JsonResponse extends Response
{
    use ConvertStreamTrait;
    use ConvertJsonTrait;
    use InjectContentTypeTrait;
    const KEY_STATUS = 'ok';
    const KEY_DATA = 'data';
    const KEY_ERROR = 'error';

    /**
     * Contains decoded body
     *
     * @var array
     */
    protected $payload;

    public function __construct($body = 'php://memory', $status = 200, array $headers = []) {
        $headers = $this->injectContentType('application/json', $headers);

        parent::__construct($body, $status, $headers);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getPayload() {
        if (null !== $this->payload) {
            return $this->payload;
        }

        /**
         * Writes a JSON stream to the string
         */
        $json = (string) $this->getBody();

        if (0 === strlen($json)) {
            throw new Exception(
                "Response body is empty, while it must be a JSON string (at least '[]')"
            );
        }

        $this->payload = $this->decodeJson($json);

        return $this->payload;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isSuccessful() {
        if ($this->getStatusCode() !== 200) {
            return false;
        }

        $payload = $this->getPayload();

        if (!isset($payload[self::KEY_STATUS])) {
            return false;
        }

        if (false === $payload[self::KEY_STATUS]) {
            return false;
        }

        return true;
    }

    /**
     * @param StreamInterface $body
     *
     * @return static
     */
    public function withBody(StreamInterface $body) {
        $previousPayload = $this->payload;

        $this->payload = null;
        $response = parent::withBody($body);

        $this->payload = $previousPayload;

        return $response;
    }

    /**
     * @param array $payload
     *
     * @return static
     */
    public function withPayload(array $payload) {
        $previousPayload = $this->payload;

        $json = $this->encodeJson($payload);
        $body = $this->createStreamFromResource($json);

        $this->payload = $payload;
        $response = $this->withBody($body);

        $this->payload = $previousPayload;

        return $response;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData() {
        $payload = $this->getPayload();

        if (!isset($payload[self::KEY_DATA])) {
            return [];
        }

        return $payload[self::KEY_DATA];
    }
}