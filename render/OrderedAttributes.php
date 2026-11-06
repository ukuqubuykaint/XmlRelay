<?php

declare(strict_types=1);

namespace XmlRelay\Render;

final class OrderedAttributes
{
    /** @param array<string, string> $attributes */
    public function __construct(private array $attributes)
    {
    }

    /** @return array<string, string> */
    public function all(): array
    {
        return $this->attributes;
    }
}
