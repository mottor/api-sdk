<?php

namespace Mottor\Api\Domain\Request\Factory;

use Mottor\Api\Domain\Common\Behavior\ConvertStreamTrait;
use Mottor\Api\Domain\Request\Model\PostRequest;
use Psr\Http\Message\RequestInterface;

class RequestFactory
{
    use ConvertStreamTrait;

    /**
     * Creates a standardized request
     * according to mottor api request conventions
     * that compatible with PSR request interface
     *
     * @param string $uri
     * @param string $secretKey
     * @param array  $postParameters
     *
     * @return RequestInterface
     */
    public function createPostRequest($uri, $secretKey, $postParameters = []) {
        $headers = [
            'X-Auth' => $secretKey
        ];

        $body = http_build_query($postParameters, null, '&');
        $body = $this->createStreamFromResource($body);

        return new PostRequest($uri, 'POST', $body, $headers);
    }
}