<?php

namespace Mottor\Api\Test\Common;

use Mottor\Api\Domain\Common\Behavior\ConvertStreamTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use ReflectionMethod;

class ConvertStreamTraitTest extends TestCase
{
    public function testThatResultMatchesTheCoreFunction() {
        $mock = $this->getMockForTrait(ConvertStreamTrait::class);

        $method = new ReflectionMethod(get_class($mock), 'createStreamFromResource');
        $method->setAccessible(true);

        $stream = $method->invoke($mock, 'HTTP/1.1 200 OK');

        $this->assertInstanceOf(StreamInterface::class, $stream);
    }
}