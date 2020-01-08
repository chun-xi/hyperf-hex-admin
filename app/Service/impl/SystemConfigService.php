<?php


namespace App\Service\impl;


use App\Constant\Cache;
use App\Model\SystemConfig;
use App\Service\SystemConfigServiceInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Utils\Codec\Json;

class SystemConfigService implements SystemConfigServiceInterface
{

    /**
     * @Inject()
     * @var \Redis
     */
    protected Redis $redis;

    /**
     * 获取配置项
     * @param string $key
     * @return mixed
     */
    public function getConfig(string $key)
    {
        $cache = sprintf(Cache::SYSTEM_CONFIG, $key);
        $options = $this->redis->get($cache);
        if (!$options) {
            $config = SystemConfig::query()->where("key", $key)->first();

            if ($config != null) {
                $options = Json::decode($config->options, true);
                $this->redis->set($cache, $options);
            }
        }
        return $options;
    }
}