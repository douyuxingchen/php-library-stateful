<?php
namespace Douyuxingchen\PhpLibraryStateful\Lock;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Redis\RedisManager;

class RedisLock {

    // 锁的key
    private $lockKey;
    // 锁默认超时时间（秒）
    private $lockTimeout = 5;
    // 锁的token
    private $lockToken;
    // 是否单元测试
    private $isTests;

    private $redis;

    public function __construct(string $lockKey) {
        $this->lockKey = $lockKey;
        if (!$this->lockToken) {
            $this->lockToken = uniqid();
        }
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
     * 自定义 lockTime(秒)
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
     * 获取key的获取时间
     *
     * @return int
     */
    public function getTtl() : int
    {
        return $this->redisClient()->ttl($this->lockKey);
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

    public function getToken(): string
    {
        return $this->lockToken;
    }

    public function setTests(): RedisLock
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
        return (bool)$this->redisClient()->eval(RedisLua::luaLock,1,
            $this->lockKey,
            $this->lockToken,
            $this->lockTimeout);
    }

    /**
     * 自旋锁
     *
     * @param int $timeout 超时秒数
     * @return bool
     */
    public function spinLock(int $timeout): bool
    {
        $end = time() + $timeout;
        while (time() < $end) {
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
        return (bool)$this->redisClient()->eval(RedisLua::luaUnlock, 1,
            $this->lockKey,
            $this->lockToken);
    }

    /**
     * 锁手动续期
     *
     * @param int $exp 过期时间(秒)
     * @return bool
     */
    public function renew(int $exp) : bool
    {
        return (bool)$this->redisClient()->eval(RedisLua::luaRenew, 1,
            $this->lockKey,
            $exp);
    }
}