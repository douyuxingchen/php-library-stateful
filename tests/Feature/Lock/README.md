# RedisLock 使用说明

## 简介
RedisLock 是一个基于 Redis 的分布式锁封装类，用于实现分布式环境下的互斥操作。它提供了加锁、解锁、自旋锁和手动续期等功能。

## 使用方法
1. 首先，确保你的项目已经安装了 Redis 扩展，并正确配置了 Redis 连接信息。

2. 引入 RedisLock 类文件：
```php
use Douyuxingchen\PhpLibraryStateful\Lock\RedisLock;
```

3. 获取 RedisLock 的单例对象：
```php
$redisLock = RedisLock::getInstance();
```

4. 自定义设置（可选）：
- 设置锁的 Key：
  ```php
  $redisLock->setKey('my_lock');
  ```
- 设置锁的超时时间（单位：秒）：
  ```php
  $redisLock->setTimeout(10);
  ```
- 设置锁的 Token：
  ```php
  $redisLock->setToken('my_token');
  ```
- 如果在测试环境中使用，可以设置为单元测试模式，引入测试环境的 Redis 配置：
  ```php
  $redisLock->setTests();
  ```

5. 加锁：
```php
$result = $redisLock->lock();
if ($result) {
   // 成功获取锁，执行互斥操作
} else {
   // 未能获取锁，执行相应的处理
}
```

6. 自旋锁：
```php
$result = $redisLock->spinLock(10);
if ($result) {
   // 成功获取锁，执行互斥操作
} else {
   // 未能获取锁，执行相应的处理
}
```

7. 解锁：
```php
$result = $redisLock->unlock();
if ($result) {
   // 解锁成功，执行后续操作
} else {
   // 解锁失败，执行相应的处理
}
```

8. 锁手动续期：
```php
$result = $redisLock->renew();
if ($result) {
   // 续期成功，执行后续操作
} else {
   // 续期失败，执行相应的处理
}
```

9. 实践技巧
使用  `try` 和 `finally` 对加锁和解锁的操作进行包裹是为了确保无论业务逻辑是否抛出异常，锁都能被正确地释放。
```php
$redisLock = RedisLock::getInstance();
try {
    $res = $redisLock->lock();
    if($res) {
        echo '执行业务逻辑-开始' . PHP_EOL;
        sleep(3);
        echo '执行业务逻辑-结束' . PHP_EOL;
    }
} finally {
    $redisLock->unlock();
}
```

## 注意事项
- RedisLock 类采用单例模式，确保在使用时只有一个实例存在。
- 默认情况下，锁的 Key 和 Token 会使用唯一的随机字符串进行初始化，可以通过 `setKey` 和 `setToken` 方法进行自定义设置。
- 默认超时时间为 5 秒，可以通过 `setTimeout` 方法进行自定义设置。
- 使用自旋锁时，请设置适当的超时时间，以避免无限等待。

