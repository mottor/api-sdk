<?php

namespace Mottor\Api\Domain\Request\Model;

use Mottor\Api\Domain\Common\Behavior\ConvertStreamTrait;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response\InjectContentTypeTrait;

class PostRequest extends Request
{
    use ConvertStreamTrait;
    use InjectContentTypeTrait;

    const HEADER_AUTHORIZATION = 'X-Auth';

    public function __construct($uri = null, $method = 'POST', $body = 'php://temp', array $headers = []) {
        $headers = $this->injectContentType('application/x-www-form-urlencoded', $headers);

        parent::__construct($uri, $method, $body, $headers);
    }

    /**
     * @param string $secretKey
     *
     * @return static
     */
    public function withSecretKey($secretKey) {
        return $this->withHeader(self::HEADER_AUTHORIZATION, $secretKey);
    }

    /**
     * @param string $secretKey
     *
     * @return bool
     */
    public function hasSecretKey($secretKey) {
        if (!$this->hasHeader(self::HEADER_AUTHORIZATION)) {
            return false;
        }

        $requestSecretKey = $this->getHeader(self::HEADER_AUTHORIZATION)[0];

        if ($requestSecretKey !== $secretKey) {
            return false;
        }

        return true;
    }

    /**
     * @param array $parameters
     *
     * @return static
     */
    public function withParameters(array $parameters) {
        $body = http_build_query($parameters, null, '&');
        $body = $this->createStreamFromResource($body);

        return $this->withBody($body);
    }
}