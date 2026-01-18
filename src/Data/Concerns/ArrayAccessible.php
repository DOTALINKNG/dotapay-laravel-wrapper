<?php

namespace DotaPay\LaravelSdk\Data\Concerns;

use ArrayAccess;

/**
 * Provides backward-compatible array access for readonly DTO classes.
 *
 * Maps snake_case array keys to camelCase property names.
 *
 * @implements ArrayAccess<string, mixed>
 */
trait ArrayAccessible
{
    public function offsetExists(mixed $offset): bool
    {
        $property = $this->snakeToCamel((string) $offset);

        return property_exists($this, $property);
    }

    public function offsetGet(mixed $offset): mixed
    {
        $property = $this->snakeToCamel((string) $offset);

        return $this->{$property} ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        // Readonly DTO - setting values is not supported
    }

    public function offsetUnset(mixed $offset): void
    {
        // Readonly DTO - unsetting values is not supported
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [];

        foreach (get_object_vars($this) as $property => $value) {
            $key = $this->camelToSnake($property);

            if ($value instanceof self || (is_object($value) && method_exists($value, 'toArray'))) {
                $data[$key] = $value->toArray();
            } elseif (is_array($value)) {
                $data[$key] = array_map(function ($item) {
                    if (is_object($item) && method_exists($item, 'toArray')) {
                        return $item->toArray();
                    }

                    return $item;
                }, $value);
            } else {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    private function snakeToCamel(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }

    private function camelToSnake(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }
}
