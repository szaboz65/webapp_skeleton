<?php

namespace App\Domain\Session\Session;

/**
 * A PHP Session handler adapter.
 */
final class PhpSession implements SessionInterface
{
    private array $storage;

    private array $options = [
        'id' => null,
        'name' => 'app',
        'lifetime' => 7200,
        'path' => null,
        'domain' => null,
        'secure' => false,
        'httponly' => true,
        // public, private_no_expire, private, nocache
        // Setting the cache limiter to '' will turn off automatic sending of cache headers entirely.
        'cache_limiter' => 'nocache',
    ];

    /**
     * Constructor.
     *
     * @param array $options The options
     */
    public function __construct(array $options = [])
    {
        // Prevent uninitialized state
        $empty = [];
        $this->storage = &$empty;

        $keys = array_keys($this->options);
        foreach ($keys as $key) {
            if (array_key_exists($key, $options)) {
                $this->options[$key] = $options[$key];
                unset($options[$key]);
            }
        }

        foreach ($options as $key => $value) {
            ini_set('session.' . $key, $value);
        }
    }

    /**
     * Starts the session - do not use session_start().
     */
    public function start(): void
    {
        if ($this->isStarted()) {
            throw new SessionException('Failed to start the session: Already started.');
        }

        if (headers_sent($file, $line) && filter_var(ini_get('session.use_cookies'), FILTER_VALIDATE_BOOLEAN)) {
            throw new SessionException(
                sprintf(
                    'Failed to start the session because headers have already been sent by "%s" at line %d.',
                    $file,
                    $line
                )
            );
        }

        $current = session_get_cookie_params();

        $lifetime = (int)($this->options['lifetime'] ?: $current['lifetime']);
        $path = $this->options['path'] ?: $current['path'];
        $domain = $this->options['domain'] ?: $current['domain'];
        $secure = (bool)$this->options['secure'];
        $httponly = (bool)$this->options['httponly'];

        session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
        session_name($this->options['name']);
        session_cache_limiter($this->options['cache_limiter']);

        $sessionId = $this->options['id'] ?: null;
        if ($sessionId) {
            session_id($sessionId);
        }

        // Try and start the session
        if (!session_start()) {
            throw new SessionException('Failed to start the session.');
        }

        // Load the session
        $this->storage = &$_SESSION;
    }

    /**
     * Checks if the session was started.
     *
     * @return bool Session status
     */
    public function isStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Migrates the current session to a new session id while maintaining all session attributes.
     *
     * Regenerates the session ID - do not use session_regenerate_id(). This method can optionally
     * change the lifetime of the new cookie that will be emitted by calling this method.
     *
     * @throws SessionException On error
     */
    public function regenerateId(): void
    {
        if (!$this->isStarted()) {
            throw new SessionException('Cannot regenerate the session ID for non-active sessions.');
        }

        if (headers_sent()) {
            throw new SessionException('Headers have already been sent.');
        }

        if (!session_regenerate_id(true)) {
            throw new SessionException('The session ID could not be regenerated.');
        }
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
     *
     * @throws SessionException On error
     */
    public function destroy(): void
    {
        if (!$this->isStarted()) {
            return;
        }

        $this->clear();

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                $this->getName(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        if (session_unset() === false) {
            throw new SessionException('The session could not be unset.');
        }

        if (session_destroy() === false) {
            throw new SessionException('The session could not be destroyed.');
        }
    }

    /**
     * Returns the session ID.
     *
     * @return string The session ID
     */
    public function getId(): string
    {
        return (string)session_id();
    }

    /**
     * Returns the session name.
     *
     * @return string The session name
     */
    public function getName(): string
    {
        return (string)session_name();
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
     *
     * @throws SessionException On error
     */
    public function save(): void
    {
        session_write_close();
    }
}
