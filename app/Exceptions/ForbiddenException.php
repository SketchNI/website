<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ForbiddenException extends Exception
{
    protected $code = HttpResponse::HTTP_FORBIDDEN;
}
