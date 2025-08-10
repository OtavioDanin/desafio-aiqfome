<?php

declare(strict_types=1);

namespace Modules\Common\Domain;

interface JwtServiceInterface
{
    public static function generateToken();
}