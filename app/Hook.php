<?php

namespace App;

class Hook
{
    private static array $hooks = [];

    // Register a hook
    public static function register(string $hookName, callable $callback): void
    {
        if (!isset(self::$hooks[$hookName])) {
            self::$hooks[$hookName] = [];
        }

        self::$hooks[$hookName][] = $callback;
    }

    // Execute a hook and pass arguments
    public static function trigger(string $hookName, array &$entries): void
    {
        if (isset(self::$hooks[$hookName])) {
            foreach (self::$hooks[$hookName] as $callback) {
                $callback($entries);
            }
        }
    }

    // Get all hooks as a JSON-compatible structure
    public static function get(?string $hookName = null): array
    {
        if ($hookName) {
            return self::$hooks[$hookName] ?? [];
        }

        return self::$hooks;
    }
}
