<?php

namespace Mottor\Api\Domain\Request\Model;

use Zend\Diactoros\Request;
use Zend\Diactoros\Response\InjectContentTypeTrait;

class PostRequest extends Request
{
    use InjectContentTypeTrait;

    public function __construct($uri = null, $method = 'POST', $body = 'php://temp', array $headers = []) {
        $headers = $this->injectContentType('application/x-www-form-urlencoded', $headers);

        parent::__construct($uri, $method, $body, $headers);
    }
}