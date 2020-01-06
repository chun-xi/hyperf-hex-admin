<?php
declare(strict_types=1);

namespace App\Controller\System;


use App\Controller\HexBaseController;
use App\Exception\HexException;
use App\Utils\StringUtil;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\AuthMiddleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Psr\Http\Message\ResponseInterface;


/**
 * Class SystemOtherController
 * @package App\Controller\System
 * @Controller(prefix="/system/other")
 * @Middleware(AuthMiddleware::class)
 */
class SystemOtherController extends HexBaseController
{

    /**
     * @return ResponseInterface
     * @PostMapping(path="upload")
     */
    public function upload(): ResponseInterface
    {
        $uploadedFiles = $this->request->file('file');
        if ($uploadedFiles == null) {
            throw new HexException("请选择文件在进行上传");
        }
        $extension = $uploadedFiles->getExtension();

        $allowExts = ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'zip', 'rar', '.gz'];

        if (!in_array($extension, $allowExts)) {
            throw new HexException("不允许的后缀");
        }

        $fileName = StringUtil::generateRandStr(16) . '.' . $extension;

        $date = date('Ymd');

        $pushDir = BASE_PATH . '/public/resource/' . $date . '/';

        if (!is_dir($pushDir)) {
            mkdir($pushDir, 777, true);
        }

        try {
            $uploadedFiles->moveTo($pushDir . $fileName);
        } catch (\Exception $e) {
            throw new HexException($e->getMessage(), $e->getCode());
        }
        if (!$uploadedFiles->isMoved()) {
            throw new HexException("文件上传失败");
        }

        $static = '/resource/' . $date . '/' . $fileName;

        return $this->response->json($this->getJson(200, '上传成功', ['path' => $static]));
    }
}