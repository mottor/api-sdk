<?php

namespace Mottor\Api\Domain\Common\Behavior;

use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\Stream;

trait ConvertStreamTrait
{
    /**
     * @param mixed $resource
     *
     * @return StreamInterface
     */
    protected function createStreamFromResource($resource) {
        $body = new Stream('php://temp', 'wb+');
        $body->write($resource);
        $body->rewind();

        return $body;
    }
}