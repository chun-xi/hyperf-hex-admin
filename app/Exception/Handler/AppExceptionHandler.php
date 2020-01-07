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
use Hyperf\Di\Annotation\Inject;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @Inject()
     * @var StdoutLoggerInterface
     */
    protected $logger;

    /**
     * 获取错误异常json
     * @param Throwable $throwable
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    private function getErrorJson(Throwable $throwable, ResponseInterface $response)
    {
        return $response->withStatus(200)->withHeader("content-type", "application/json;chartset=uft-8")->withBody(new SwooleStream(Json::encode(['code' => $throwable->getCode(), 'msg' => $throwable->getMessage()])));
    }

    /**
     * 异常处理类
     * @param Throwable $throwable
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof HexException) {
            return $this->getErrorJson($throwable, $response);
        }
        try {
            $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
            $this->logger->error($throwable->getTraceAsString());
        } catch (\Exception $e) {
            return $this->getErrorJson($throwable, $response);
        } catch (\Error $e) {
            return $this->getErrorJson($throwable, $response);
        }
        return $this->getErrorJson($throwable, $response);
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
