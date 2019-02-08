<?php

namespace Mottor\Api\Domain\Factory;

use function GuzzleHttp\Psr7\stream_for;
use Zend\Diactoros\Request;

class RequestFactory
{
    /**
     * Creates a standardized request
     * according to mottor api request conventions
     * that compatible with PSR request interface
     *
     * @param string $uri
     * @param string $secretKey
     * @param array  $postParameters
     *
     * @see https://collab.lpmotor.ru:8090/confluence/pages/viewpage.action?pageId=11372673
     *
     * @return Request
     */
    public function create($uri, $secretKey, $postParameters = []) {
        $headers = [
            'X-Auth'       => $secretKey,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $requestBody = http_build_query($postParameters, null, '&');
        $requestBody = stream_for($requestBody);

        return new Request($uri, 'POST', $requestBody, $headers);
    }
}