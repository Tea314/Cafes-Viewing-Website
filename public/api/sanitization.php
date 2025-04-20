<?php

const FILTERS = [
    'string' => 'custom_string',
    'string[]' => [
        'filter' => 'custom_string',
        'flags' => FILTER_REQUIRE_ARRAY,
    ],
    'email' => FILTER_SANITIZE_EMAIL,
    'int' => [
        'filter' => FILTER_SANITIZE_NUMBER_INT,
        'flags' => FILTER_REQUIRE_SCALAR,
    ],
    'int[]' => [
        'filter' => FILTER_SANITIZE_NUMBER_INT,
        'flags' => FILTER_REQUIRE_ARRAY,
    ],
    'float' => [
        'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
        'flags' => FILTER_FLAG_ALLOW_FRACTION,
    ],
    'float[]' => [
        'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
        'flags' => FILTER_REQUIRE_ARRAY,
    ],
    'url' => FILTER_SANITIZE_URL,
];

/**
 * Custom string sanitization
 */
function sanitize_string($value)
{
    if (is_array($value)) {
        return array_map('sanitize_string', $value);
    }

    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}

/**
 * Recursively trim strings in an array
 */
function array_trim(array $items): array
{
    return array_map(function ($item) {
        if (is_string($item)) {
            return trim($item);
        } elseif (is_array($item)) {
            return array_trim($item);
        } else {
            return $item;
        }
    }, $items);
}

/**
 * Sanitize the inputs based on the rules and optionally trim the string
 */
function sanitize(array $inputs, array $fields = [], $default_filter = 'custom_string', array $filters = FILTERS, bool $trim = true): array
{
    $data = [];
    if ($fields) {
        foreach ($fields as $field => $filterType) {
            if (! isset($inputs[$field])) {
                $data[$field] = '';

                continue;
            }
            $value = $inputs[$field];
            if ($filterType === 'custom_string') {
                $data[$field] = sanitize_string($value);
            } else {
                $options = $filters[$filterType] ?? $default_filter;
                if ($options === 'custom_string') {
                    $data[$field] = sanitize_string($value);
                } elseif (is_array($options)) {
                    $data[$field] = filter_var($value, $options['filter'], $options);
                } else {
                    $data[$field] = filter_var($value, $options);
                }
            }
        }
    } else {
        foreach ($inputs as $field => $value) {
            $data[$field] = sanitize_string($value);
        }
    }

    return $trim ? array_trim($data) : $data;
}
