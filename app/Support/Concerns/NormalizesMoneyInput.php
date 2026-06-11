<?php

namespace App\Support\Concerns;

trait NormalizesMoneyInput
{
    protected function normalizeMoneyFields(array $fields): void
    {
        $normalized = [];

        foreach ($fields as $field) {
            $value = $this->input($field);

            if ($value === null || $value === '') {
                continue;
            }

            $normalized[$field] = $this->normalizeMoneyInput($value);
        }

        $this->merge($normalized);
    }

    protected function normalizeMoneyInput(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if (is_int($value)) {
            return $value;
        }

        $raw = trim((string) $value);

        if (! preg_match('/^(?:0|[1-9]\d*|[1-9]\d{0,2}(?:\.\d{3})*)$/', $raw)) {
            return $value;
        }

        return (int) str_replace('.', '', $raw);
    }
}
