<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\HexException;

class IndexController extends HexBaseController
{
    public function index()
    {
        return 'hello';
    }
}
