<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

return [
    \Hyperf\Validation\Contract\ValidatorFactoryInterface::class => \Hyperf\Validation\ValidatorFactory::class,
    \App\Service\SystemUserServiceInterface::class => \App\Service\impl\SystemUserService::class,
    \App\Service\SystemMenuServiceInterface::class => \App\Service\impl\SystemMenuService::class,
    \App\Service\SystemDictServiceInterface::class => \App\Service\impl\SystemDictService::class
];
