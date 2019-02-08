<?php

namespace Mottor\Api\Domain\Request\Model;

use Mottor\Api\Domain\Common\Behavior\ConvertStreamTrait;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response\InjectContentTypeTrait;

class PostRequest extends Request
{
    use ConvertStreamTrait;
    use InjectContentTypeTrait;

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
        return $this->withHeader('X-Auth', $secretKey);
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