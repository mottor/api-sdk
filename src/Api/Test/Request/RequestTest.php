<?php

namespace Mottor\Api\Test\Request;

use Mottor\Api\Domain\Request\Factory\RequestFactory;
use Mottor\Api\Domain\Request\Model\PostRequest;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testThatRequestCreatesCorrectly() {
        $uri = 'https://example.com';

        $request = new PostRequest($uri);
        $request = $request
            ->withSecretKey('qwerty')
            ->withParameters([
                'first'  => 'data',
                'second' => 'data'
            ]);

        $body = (string) $request->getBody();

        $this->assertEquals('first=data&second=data', urldecode($body));

        $request = new PostRequest($uri);
        $request = $request
            ->withSecretKey('qwerty')
            ->withParameters([
                    'data' => [
                        ['first' => 'data', 'second' => 'data'],
                    ]
                ]
            );

        $body = (string) $request->getBody();

        $this->assertEquals('data[0][first]=data&data[0][second]=data', urldecode($body));
    }
}