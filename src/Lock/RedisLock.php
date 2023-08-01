<?php
namespace Douyuxingchen\PhpLibraryStateful\Lock;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Redis\RedisManager;

class RedisLock {

    // 锁的key
    private $lockKey;
    // 超时秒数
    private $lockTimeout = 5;
    // 锁的token
    private $lockToken;
    // 是否单元测试
    private $isTests;

    private $redis;
    private static $instance;

    private function __construct() {
        if (!$this->lockKey) {
            $this->lockKey = uniqid();
        }
        if (!$this->lockToken) {
            $this->lockToken = uniqid();
        }
    }

    public static function getInstance(): RedisLock
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function redisClient(): Connection
    {
        if(!$this->isTests) {
            if($this->redis) {
                return $this->redis;
            } else {
                $this->redis = Redis::connection('cache');
            }
            return $this->redis;
        }

        $config = [
            'client' => 'predis',
            'default' => [
                'host' => lib_env('REDIS_HOST'),
                'port' => lib_env('REDIS_PORT'),
                'password' => lib_env('REDIS_PASSWORD'),
                'database' => lib_env('REDIS_DB'),
            ]
        ];
        $redisManager = new RedisManager(app(), 'predis', $config);
        $this->redis = $redisManager->resolve('default');
        return $this->redis;
    }

    /**
     * 自定义 lockKey
     *
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): RedisLock
    {
        $this->lockKey = $key;
        return $this;
    }

    /**
     * 自定义 lockTime(单位：秒)
     *
     * @param int $timeout
     * @return $this
     */
    public function setTimeout(int $timeout): RedisLock
    {
        $this->lockTimeout = $timeout;
        return $this;
    }

    /**
     * 自定义 lockToken
     *
     * @param string $token
     * @return $this
     */
    public function setToken(string $token): RedisLock
    {
        $this->lockToken = $token;
        return $this;
    }

    public function setTests()
    {
        $this->isTests = true;
        return $this;
    }

    /**
     * 加锁
     *
     * @return bool
     */
    public function lock(): bool
    {
        return (bool)$this->redisClient()->set($this->lockKey, $this->lockToken, 'NX', 'EX', $this->lockTimeout);
    }

    /**
     * 自旋锁
     *
     * @param int $timeout 超时秒数
     * @return bool
     */
    public function spinLock(int $timeout): bool
    {
        $start = microtime(true);
        $end = $start + $timeout*1000;
        while (microtime(true) < $end) {
            if ($this->lock()) {
                return true;
            }
            usleep(100000); // 100ms
        }
        return false;
    }

    /**
     * 解锁
     *
     * @return bool
     */
    public function unlock(): bool
    {
        $lockToken = $this->redisClient()->get($this->lockKey);
        if ($lockToken === $this->lockToken) {
            $this->redisClient()->del($this->lockKey);
            return true;
        }
        return false;
    }

    /**
     * 锁手动续期
     *
     * @return bool
     */
    public function renew() : bool
    {
        return (bool)$this->redisClient()->expire($this->lockKey, $this->lockTimeout);
    }
}