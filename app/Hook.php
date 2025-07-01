<?php

namespace App;

use App\Exceptions\HookTypeException;
use App\Types\HookType;

class Hook
{
    private static array $hooks = [];

    /**
     * Registers a callback function to be executed for the specified hook name.
     *
     * @param  string  $hookName  Name of the hook to register the callback for.
     * @param  callable  $callback  The callback function to be executed when the hook is triggered.
     *
     * @return void
     *
     * @throws HookTypeException If the provided hook name is not a valid HookType.
     */
    public static function register(string $hookName, callable $callback): void
    {
        // Validate that the hook name is a valid HookType
        $validHooks = array_map(fn ($case) => $case->value, HookType::cases());

        if (!in_array($hookName, $validHooks)) {
            throw new HookTypeException("Invalid hook name: {$hookName}");
        }

        if (!isset(self::$hooks[$hookName])) {
            self::$hooks[$hookName] = [];
        }

        self::$hooks[$hookName][] = $callback;
    }

    /**
     * Triggers all callbacks associated with the specified hook name.
     *
     * @param  string  $hookName  Name of the hook to trigger.
     * @param  array  &$entries  Reference to the entries to be passed to the callbacks.
     *
     * @return void
     */
    public static function trigger(string $hookName, array &$entries): void
    {
        if (isset(self::$hooks[$hookName])) {
            foreach (self::$hooks[$hookName] as $callback) {
                $callback($entries);
            }
        }
    }

    /**
     * Retrieves a set of hooks from the internal hooks collection.
     *
     * @param  string|null  $hookName  Optional parameter to specify a hook name.
     *                                 If provided, returns the associated hook set
     *                                 or an empty array if the hook does not exist.
     *                                 If null, returns the entire hooks' collection.
     *
     * @return array
     */
    public static function get(?string $hookName = null): array
    {
        if ($hookName) {
            return self::$hooks[$hookName] ?? [];
        }

        return self::$hooks;
    }
}
