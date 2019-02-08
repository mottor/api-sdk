<?php

use Mottor\Api\Domain\Factory\RequestFactory;

class FactoryTest
{
    public function testThatFactoryCorrectlyCreatesRequests() {
        $factory = new RequestFactory();
        $request = $factory->create('https://example.com', 'qwerty', ['first' => 'data', 'second' => 'data']);

        $this->assertEquals('https://example.com', $request->getUri());
        $this->assertEquals('qwerty', $request->getHeader('X-Auth')[0]);
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeader('Content-Type')[0]);

        $requestBody = (string) $request->getBody();

        $this->assertEquals('first=data&second=data', urldecode($requestBody));

        $request2 = $factory->create('https://example.com', 'qwerty', [
            'data' => [
                ['first' => 'data', 'second' => 'data'],
            ]
        ]);

        $requestBody = (string) $request2->getBody();

        $this->assertEquals('data[0][first]=data&data[0][second]=data', urldecode($requestBody));
    }
}