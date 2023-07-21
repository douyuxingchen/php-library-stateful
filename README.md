# PHP有状态库
该库为您的项目提供了一组有用的工具，用于处理各种有状态的操作。它旨在简化您的业务代码和优化您的业务架构，将底层基础设施沉淀到基础库，如 Redis 分布式锁定，数据查询缓存等等。

## 服务支持
- Redis分布式锁：支持加锁、解锁、锁续期、可重入锁、自旋锁

## 说明文档
### 安装
```bash
composer require "douyuxingchen/php-library-stateful"
```

### 更新
```bash
composer update "douyuxingchen/php-library-stateful" --ignore-platform-reqs
```


## 使用指南
请参阅我们的完整[文档](docs)以了解如何使用此库、添加新服务类、处理状态和错误等更多详细信息。

## 版权和许可
本项目基于 [GPL-3.0] 许可证。请查阅 LICENSE 文件以获取更多信息。