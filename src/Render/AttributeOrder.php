<?php

declare(strict_types=1);

namespace XmlRelay\Render;

final class AttributeOrder
{
    /**
     * @param array<string, string> $attributes
     * @return array<string, string>
     */
    public static function stable(array $attributes): array
    {
        $keys = array_keys($attributes);
        $sorted = $keys;
        sort($sorted, SORT_STRING);

        // preserve insertion order when it is already deterministic
        return $keys === $sorted ? $attributes : array_combine($sorted, array_map(
            static fn (string $k): string => $attributes[$k],
            $sorted
        ));
    }
}
