<?php
namespace Tests\Unit;

use Douyuxingchen\PhpLibraryStateful\Lock\RedisLock;
use PHPUnit\Framework\TestCase;

class RedisLockTest extends TestCase
{
    // 测试加锁成功
    public function testLock()
    {
        $redisLock = (new RedisLock('lock_key'))
            ->setTests();
        try {
            $res = $redisLock->lock();
            if($res) {
                echo "执行业务逻辑-开始" . PHP_EOL;
                sleep(3);
                echo "执行业务逻辑-结束" . PHP_EOL;
            }
            $this->assertTrue($res);
        } finally {
            $res2 = $redisLock->unlock();
            $this->assertTrue($res2);
        }
    }

    // 测试加锁超时时间
    public function testLockTimeout()
    {
        $redisLock = (new RedisLock('lock_key_testLockTimeout'))
            ->setTests();
        $redisLock->setTimeout(20);
        $res = $redisLock->lock();
        echo sprintf("过期时间：%d \n", $redisLock->getTtl());
        $this->assertTrue($res);
    }

    // 测试竞争锁失败
    public function testLockFailed()
    {
        $redisLock = (new RedisLock('lock_key2'))
            ->setTests();

        $res1 = $redisLock->lock();
        $this->assertTrue($res1);

        // 抢夺上方的锁，预期：竞争失败
        $res2 =$redisLock->lock();
        $this->assertFalse($res2);
    }

    public function SpinLock(string $lockKey, int $timeout)
    {
        $redisLock = (new RedisLock($lockKey))
            ->setTests()
            ->setTimeout($timeout);
        $res1 = $redisLock->spinLock($timeout);
        $this->assertTrue($res1);
    }

    // 测试自旋锁获取成功
    public function testSpinLock()
    {
        $lockKey = 'lock_key3';

        echo '方法开始执行 ' . date('Y-m-d H:i:s') . PHP_EOL;
        $this->SpinLock($lockKey,3);

        $redisLock = (new RedisLock($lockKey))
            ->setTests();

        echo '开始获取自旋锁 ' . date('Y-m-d H:i:s') . PHP_EOL;
        $res1 = $redisLock->spinLock(10);
        echo '自旋锁返回结果 ' . date('Y-m-d H:i:s') . PHP_EOL;
        $this->assertTrue($res1);
    }

    // 测试自旋锁获取超时
    public function testSpinLockTimeout()
    {
        $lockKey = 'lock_key_testSpinLockTimeout';

        echo '方法开始执行 ' . date('Y-m-d H:i:s') . PHP_EOL;
        $this->SpinLock($lockKey, 10);

        $redisLock = (new RedisLock($lockKey))
            ->setTests();

        echo '开始获取自旋锁 ' . date('Y-m-d H:i:s') . PHP_EOL;
        $res1 = $redisLock->spinLock(3);
        echo '自旋锁返回结果 ' . date('Y-m-d H:i:s') . PHP_EOL;
        $this->assertFalse($res1);
    }

}