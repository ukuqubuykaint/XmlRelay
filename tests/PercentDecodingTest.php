<?php

declare(strict_types=1);

namespace XmlRelay\Tests;

use PHPUnit\Framework\TestCase;

final class PercentDecodingTest extends TestCase
{
    public function testSingleDecode(): void
    {
        $this->assertSame('/a/b', rawurldecode('%2Fa%2Fb'));
    }
}
