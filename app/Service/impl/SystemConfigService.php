<?php


namespace App\Service\impl;


use App\Constant\Cache;
use App\Exception\HexException;
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
     * @param bool $public
     * @return mixed
     */
    public function getConfig(string $key, bool $public = false)
    {
        $cache = sprintf(Cache::SYSTEM_CONFIG, $key);
        $options = $this->redis->get($cache);
        if (!$options) {
            $config = SystemConfig::query()->where("key", $key)->first();
            if ($config != null) {
                if ($public) {
                    if (!(bool)$config->public) {
                        throw new HexException("configuration is not exposed");
                    }
                }
                $options = Json::decode($config->options, true);
                $this->redis->set($cache, $options);
            }
        }
        return $options;
    }

    /**
     * 通过ID获取配置
     * @param int $id
     * @return mixed
     */
    public function findById(int $id)
    {
        $config = SystemConfig::query()->find($id);
        return $config;
    }
}