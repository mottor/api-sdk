<?php

namespace Mottor\Api\Test\Response;

use function GuzzleHttp\Psr7\stream_for;
use Mottor\Api\Domain\Response\Model\JsonResponse;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testThatResponseConvertsCorrectly() {
        $payload = [
            'property' => 'value'
        ];

        $stream = stream_for(json_encode($payload));

        $response = new JsonResponse($stream);

        $responsePayload = $response->getPayload();

        $this->assertEquals($payload, $responsePayload);
    }

    public function testThatResponsePayloadDecodesOnce() {
        $payload = [
            'property' => 1
        ];

        $stream = stream_for(json_encode($payload));

        $response = $this
            ->getMockBuilder(JsonResponse::class)
            ->setConstructorArgs([
                $stream
            ])
            ->setMethods([
                'decodeJson'
            ])
            ->getMock();

        $response
            ->expects($this->once())
            ->method('decodeJson')
            ->willReturn($payload);

        /**
         * From this moment we belive
         * that mock object is an real object
         *
         * @var JsonResponse $response
         */
        $response->getPayload();
        $response->getPayload();
    }

    public function testThatResponse() {
        $payload = [
            'property' => 1
        ];

        $secondPayload = [
            'property' => 2
        ];

        $stream = stream_for(json_encode($payload));
        $secondStream = stream_for(json_encode($secondPayload));

        $response = new JsonResponse($stream);

        // It should convert the JSON stream to payload under the hood
        $response->getPayload();

        $secondResponse = $response->withBody($secondStream);

        $this->assertEquals($payload, $response->getPayload());
        $this->assertEquals($secondPayload, $secondResponse->getPayload());
    }

    public function testThatResponsePayloadDecodesOnceForEachInstance() {
        $payload = [
            'property' => 1
        ];

        $secondPayload = [
            'property' => 2
        ];

        $stream = stream_for(json_encode($payload));
        $secondStream = stream_for(json_encode($secondPayload));

        $response = $this
            ->getMockBuilder(JsonResponse::class)
            ->setConstructorArgs([
                $stream
            ])
            ->setMethods([
                'decodeJson'
            ])
            ->getMock();

        $response
            ->expects($this->exactly(2))
            ->method('decodeJson')
            ->willReturn([]);

        /**
         * From this moment we belive
         * that response object is an real object
         *
         * It also should convert the JSON stream to payload under the hood
         * but in this case, it always is an empty array
         *
         * @var JsonResponse $response
         */

        $response->getPayload();

        $secondResponse = $response->withBody($secondStream);

        $response->getPayload();
        $secondResponse->getPayload();
    }
}