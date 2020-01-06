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

namespace App\Exception\Handler;

use App\Exception\HexException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof HexException) {
            return $response->withStatus(200)->withHeader("content-type", "application/json;chartset=uft-8")->withBody(new SwooleStream(Json::encode(['code' => $throwable->getCode(), 'msg' => $throwable->getMessage()])));
        }
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());
        return $response->withStatus(500)->withHeader("content-type", "application/json;chartset=uft-8")->withBody(new SwooleStream(Json::encode(['code' => $throwable->getCode(), 'msg' => $throwable->getMessage()])));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
