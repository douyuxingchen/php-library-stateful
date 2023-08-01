<?php
namespace Tests\Feature\Lock;

use Douyuxingchen\PhpLibraryStateful\Lock\RedisLock;
use PHPUnit\Framework\TestCase;

class RedisLockTest extends TestCase
{
    public function testLock()
    {
        $redisLock = RedisLock::getInstance()->setTests()->setKey('test_key');
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

}