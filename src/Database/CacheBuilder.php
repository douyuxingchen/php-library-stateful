<?php

namespace Douyuxingchen\PhpLibraryStateful\Database;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class CacheBuilder extends Builder {

//    /**
//     * Execute the query and get the first result from the cache or the database.
//     *
//     * @param array|string $columns
//     * @return mixed
//     * @throws InvalidArgumentException
//     */
//    public function firstCache($columns = ['*'])
//    {
//        // 1. 尝试从缓存中获取数据
//        $cacheKey = $this->getCacheKey();
//        $cachedData = Cache::store('redis')->get($cacheKey);
//
//        if ($cachedData !== null) {
//            // 如果缓存中存在数据，则直接返回
//            // return $this->hydrateResults($this->model->newCollection([$cachedData]), $columns)->first();
//
//            return $this->newQuery()->first($columns);
//        }
//
//        // 2. 从数据库中获取数据
//        $result = $this->first($columns);
//
//        if ($result !== null) {
//            // 如果数据库中存在数据，则将其缓存起来
//            Cache::store('redis')->put($cacheKey, $result->toArray(), $this->getCacheExpiration());
//        }
//
//        return $result;
//    }
//
//    /**
//     * 通过sql生成缓存的key
//     *
//     * @return string
//     */
//    protected function getCacheKey()
//    {
//        return 'cache_key_' . md5($this->toSql() . serialize($this->getBindings()));
//    }
//
//    /**
//     * 获取缓存的过期时间（秒）
//     *
//     * @return int
//     */
//    protected function getCacheExpiration()
//    {
//        return 3600; // 1 hour
//    }

}