<?php

namespace App\Domain\Session\Session;

/**
 * A memory (array) session handler adapter.
 */
final class MemorySession implements SessionInterface
{
    private array $options = [
        'name' => 'app',
        'lifetime' => 7200,
    ];

    private array $storage;

    private string $id = '';

    private bool $started = false;

    /**
     * Constructor.
     *
     * @param array $options The options
     */
    public function __construct(array $options = [])
    {
        $keys = array_keys($this->options);
        foreach ($keys as $key) {
            if (array_key_exists($key, $options)) {
                $this->options[$key] = $options[$key];
            }
        }

        $session = [];
        $this->storage = &$session;
    }

    /**
     * Starts the session - do not use session_start().
     */
    public function start(): void
    {
        if (!$this->id) {
            $this->regenerateId();
        }

        $this->started = true;
    }

    /**
     * Checks if the session was started.
     *
     * @return bool Session status
     */
    public function isStarted(): bool
    {
        return $this->started;
    }

    /**
     * Migrates the current session to a new session id while maintaining all session attributes.
     *
     * Regenerates the session ID - do not use session_regenerate_id(). This method can optionally
     * change the lifetime of the new cookie that will be emitted by calling this method.
     */
    public function regenerateId(): void
    {
        $this->id = str_replace('.', '', uniqid('sess_', true));
    }

    /**
     * Clears all session data and regenerates session ID.
     *
     * Do not use session_destroy().
     *
     * Invalidates the current session.
     *
     * Clears all session attributes and flashes and regenerates the session
     * and deletes the old session from persistence.
     */
    public function destroy(): void
    {
        $keys = array_keys($this->storage);
        foreach ($keys as $key) {
            unset($this->storage[$key]);
        }
        $this->regenerateId();
        $this->started = false;
    }

    /**
     * Returns the session ID.
     *
     * @return string The session ID
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Returns the session name.
     *
     * @return string The session name
     */
    public function getName(): string
    {
        return $this->options['name'];
    }

    /**
     * Gets an attribute by key.
     *
     * @param string $key The key name or null to get all values
     * @param mixed $default The default value
     *
     * @return mixed The value. Returns null if the key is not found
     */
    public function get(string $key, $default = null)
    {
        return $this->storage[$key] ?? $default;
    }

    /**
     * Gets all values as array.
     *
     * @return array The session values
     */
    public function all(): array
    {
        return (array)$this->storage;
    }

    /**
     * Sets an attribute by key.
     *
     * @param string $key The key of the element to set
     * @param mixed $value The data to set
     *
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->storage[$key] = $value;
    }

    /**
     * Sets multiple attributes at once: takes a keyed array and sets each key => value pair.
     *
     * @param array $values The new values
     */
    public function setValues(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->storage[$key] = $value;
        }
    }

    /**
     * Check if an attribute key exists.
     *
     * @param string $key The key
     *
     * @return bool True if the key is set or not
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->storage);
    }

    /**
     * Deletes an attribute by key.
     *
     * @param string $key The key to remove
     */
    public function delete(string $key): void
    {
        unset($this->storage[$key]);
    }

    /**
     * Clear all attributes.
     */
    public function clear(): void
    {
        $keys = array_keys($this->storage);
        foreach ($keys as $key) {
            unset($this->storage[$key]);
        }
    }

    /**
     * Force the session to be saved and closed.
     *
     * This method is generally not required for real sessions as the session
     * will be automatically saved at the end of code execution.
     */
    public function save(): void
    {
    }
}
