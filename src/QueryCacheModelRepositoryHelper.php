<?php

namespace HalcyonLaravel\Base;

use Closure;
use Exception;
use Illuminate\Support\Arr;
use ReflectionObject;

class QueryCacheModelRepositoryHelper
{
    /**
     * @var
     */
    private static $storeFile;
    /**
     * @var \Illuminate\Cache\CacheManager|\Illuminate\Foundation\Application|mixed
     */
    private $cache;

    /**
     * QueryCacheModelRepositoryHelper constructor.
     */
    public function __construct()
    {
        self::getFilePath();
        $this->cache = app('cache');
    }

    /**
     * @return string
     */
    public static function getFilePath(): string
    {
        return self::$storeFile = storage_path('framework/cache/query-cache-model-repository.json');
    }

    /**
     * @param $keys
     * @param  \Closure  $closure
     *
     * @return mixed
     */
    public function queryCache($keys, Closure $closure)
    {
        $keys = Arr::wrap($keys);

        $r = new ReflectionObject($closure);
        $keys[] = md5((string) $r);

        $key = $this->getKeys($keys);

        self::storeKeys($key);

        return $this->cache->remember($key, config('repository.cache.minutes'), $closure);
    }

    /**
     * @param  array  $args
     *
     * @return string
     */
    private function getKeys(array $args): string
    {
        // get who call this
        $backTrace = debug_backtrace()[2];
        try {
            $called = "{$backTrace['class']}@{$backTrace['function']}";
        } catch (Exception $e) {
            $called = 'noneClass@xxx';
        }

        return sprintf('%s:%s-%s',
            current_base_url(),
            $called,
            md5($called.serialize(implode('-', $args)).app('request')->fullUrl())
        );
    }

    /**
     * @param $key
     */
    private static function storeKeys($key)
    {
        $content = self::getStoredKeys();

        if (!in_array($key, $content)) {
            $content[] = $key;
        }

        self::putToJson($content);
    }

    /**
     * @return array
     */
    private static function getStoredKeys(): array
    {
        if (!file_exists(self::$storeFile)) {
            self::putToJson([]);
            return [];
        }
        return json_decode(file_get_contents(self::$storeFile), true) ?: [];
    }

    /**
     * @param  array  $data
     */
    private static function putToJson(array $data)
    {
//        JSON_PRETTY_PRINT
        file_put_contents(self::$storeFile, json_encode($data));
    }

    /**
     *
     */
    public function flush()
    {
        foreach (self::getStoredKeys() as $key) {
            $this->cache->forget($key);
        }
        self::putToJson([]);
    }
}