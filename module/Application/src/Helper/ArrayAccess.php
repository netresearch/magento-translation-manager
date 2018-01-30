<?php
namespace Application\Helper;

/**
 * A helper class for extracting values from an array.
 */
class ArrayAccess
{
    /**
     * Extracts an integer from a certain key of a given input array, and returns it.
     *
     * @param array  $array   The input array
     * @param string $key     An array key
     * @param int    $default Default value to return
     *
     * @return int
     */
    public static function getInt(array $array, string $key, int $default = 0): int
    {
        if (array_key_exists($key, $array)) {
            return (int) $array[$key];
        }

        return $default;
    }

    /**
     * Extracts a string from a certain key of a given input array, and returns it.
     *
     * @param array  $array   The input array
     * @param string $key     An array key
     * @param string $default Default value to return
     *
     * @return string|null
     */
    public static function getString(array $array, string $key, ?string $default = null): ?string
    {
        if (array_key_exists($key, $array)) {
            return (string) $array[$key];
        }

        return $default;
    }

    /**
     * Extracts a boolean from a certain key of a given input array, and returns it.
     *
     * @param array  $array   The input array
     * @param string $key     An array key
     * @param bool   $default Default value to return
     *
     * @return bool
     */
    public static function getBool(array $array, string $key, bool $default = false): bool
    {
        if (array_key_exists($key, $array)) {
            return (bool) $array[$key];
        }

        return $default;
    }
}
