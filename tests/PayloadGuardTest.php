<?php

declare(strict_types=1);

namespace XmlRelay\Tests;

use PHPUnit\Framework\TestCase;
use XmlRelay\Validation\PayloadGuard;

final class PayloadGuardTest extends TestCase
{
    public function testRejectsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        PayloadGuard::assert('');
    }

    public function testAcceptsPlainXml(): void
    {
        PayloadGuard::assert('<a/>');
        $this->addToAssertionCount(1);
    }
}
