<?php

namespace malkusch\lock\mutex;

use malkusch\lock\util\Loop;
use malkusch\lock\exception\LockReleaseException;
use malkusch\lock\exception\LockAcquireException;

/**
 * Spinlock implementation.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @internal
 */
abstract class SpinlockMutex extends LockMutex
{
    
    /**
     * @var int The timeout in seconds a lock may live.
     */
    private $timeout;
    
    /**
     * @var Loop The loop.
     */
    private $loop;
    
    /**
     * @var string The lock key.
     */
    private $key;
    
    /**
     * @var double The timestamp when the lock was acquired.
     */
    private $acquired;
    
    /**
     * The prefix for the lock key.
     */
    const PREFIX = "lock_";
    
    /**
     * Sets the timeout.
     *
     * @param int $timeout The time in seconds a lock expires, default is 3.
     *
     * @throws \LengthException The timeout must be greater than 0.
     */
    public function __construct($name, $timeout = 3)
    {
        $this->timeout = $timeout;
        $this->loop    = new Loop($this->timeout);
        $this->key     = static::PREFIX.$name;
    }
    
    protected function lock()
    {
        $this->loop->execute(function () {
            $this->acquired = microtime(true);
            if ($this->acquire($this->key, $this->timeout + 1)) {
                $this->loop->end();
            }
        });
    }

    protected function unlock()
    {
        $elapsed = microtime(true) - $this->acquired;
        if ($elapsed >= $this->timeout) {
            $message = sprintf(
                "The code executed for %d seconds. But the timeout is %d seconds.",
                $elapsed,
                $this->timeout
            );
            throw new LockReleaseException($message);
        }

        /*
         * Worst case would still be one second before the key expires.
         * This guarantees that we don't delete a wrong key.
         */
        if (!$this->release($this->key)) {
            throw new LockReleaseException("Failed to release the lock.");
        }
    }
    
    /**
     * Tries to acquire a lock.
     *
     * @param string $key The lock key.
     * @param int $expire The timeout in seconds when a lock expires.
     *
     * @return bool True, if the lock could be acquired.
     * @throws LockAcquireException An unexpected error happened.
     */
    abstract protected function acquire($key, $expire);

    /**
     * Tries to release a lock.
     *
     * @param string $key The lock key.
     * @return bool True, if the lock could be released.
     */
    abstract protected function release($key);
}
