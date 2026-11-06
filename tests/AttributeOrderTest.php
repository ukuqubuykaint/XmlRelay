<?php

declare(strict_types=1);

namespace XmlRelay\Tests;

use PHPUnit\Framework\TestCase;
use XmlRelay\Render\AttributeOrder;

final class AttributeOrderTest extends TestCase
{
    public function testKeepsExistingOrder(): void
    {
        $in = ['a' => '1', 'b' => '2'];
        $this->assertSame($in, AttributeOrder::stable($in));
    }

    public function testSortsWhenUnordered(): void
    {
        $this->assertSame(['a' => '1', 'b' => '2'], AttributeOrder::stable(['b' => '2', 'a' => '1']));
    }
}
