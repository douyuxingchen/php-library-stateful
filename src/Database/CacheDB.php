<?php

namespace Douyuxingchen\PhpLibraryStateful\Database;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB as BaseDB;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;


class CacheDB extends BaseDB {

    /**
     * Get a database connection instance.
     *
     * @param  string|null  $name
     * @return ConnectionInterface
     */
//    public static function connection($name = null)
//    {
//        $connection = parent::connection($name);
//
//        // 使用自定义的查询构造器类 CacheBuilder
//        $connection->setQueryGrammar($connection->getQueryGrammar());
//        $connection->setPostProcessor($connection->getPostProcessor());
//        $connection->setQueryBuilder(new CacheBuilder($connection, $connection->getQueryGrammar(), $connection->getPostProcessor()));
//
//        return $connection;
//    }

//    /**
//     * Get a database connection instance.
//     *
//     * @param  string|null  $name
//     * @return ConnectionInterface
//     */
//    public static function connection($name = null)
//    {
//        $instance = parent::connection($name);
//
//        // 设置自定义的查询构造器
//        $instance->setQueryGrammar($instance->withTablePrefix(new Grammar));
//        $instance->setPostProcessor(new Processor);
//        $instance->useDefaultQueryGrammar();
//        $instance->useDefaultPostProcessor();
//
//        return $instance;
//    }
//
//    /**
//     * Get a new query builder instance.
//     *
//     * @return CacheBuilder
//     */
//    public function query()
//    {
//        return new CacheBuilder($this, $this->getQueryGrammar(), $this->getPostProcessor());
//    }


}