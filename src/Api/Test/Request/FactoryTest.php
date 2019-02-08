<?php

namespace Mottor\Api\Test\Request;

use Mottor\Api\Domain\Request\Factory\RequestFactory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testThatFactoryCorrectlyCreatesRequests() {
        $factory = new RequestFactory();
        $request = $factory->createPostRequest('https://example.com', 'qwerty', ['first' => 'data', 'second' => 'data']);

        $this->assertEquals('https://example.com', $request->getUri());
        $this->assertEquals('qwerty', $request->getHeader('X-Auth')[0]);
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeader('Content-Type')[0]);

        $requestBody = (string) $request->getBody();

        $this->assertEquals('first=data&second=data', urldecode($requestBody));

        $request2 = $factory->createPostRequest('https://example.com', 'qwerty', [
            'data' => [
                ['first' => 'data', 'second' => 'data'],
            ]
        ]);

        $requestBody = (string) $request2->getBody();

        $this->assertEquals('data[0][first]=data&data[0][second]=data', urldecode($requestBody));
    }
}