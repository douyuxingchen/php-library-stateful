<?php
namespace Douyuxingchen\PhpLibraryStateful\Lock;

class RedisLua {

    const luaLock = <<<LUA
        local key = KEYS[1]
        local token = ARGV[1]
        local timeout = tonumber(ARGV[2])
        
        if redis.call('SET', key, token, 'NX', 'EX', timeout) then
            return true
        else
            return false
        end 
LUA;


    const luaUnlock = <<<LUA
        local key = KEYS[1]
        local token = ARGV[1]
        
        if redis.call('GET', key) == token then
            return redis.call('DEL', key)
        else
            return 0
        end
LUA;


    const luaRenew = <<<LUA
        local key = KEYS[1]
        local exp = tonumber(ARGV[1])
        
        return redis.call('EXPIRE', key, exp)
LUA;

}