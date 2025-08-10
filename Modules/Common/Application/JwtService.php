<?php

declare(strict_types=1);

namespace Modules\Common\Application;

use DomainException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use InvalidArgumentException;
use Modules\Common\Domain\JwtServiceInterface;
use UnexpectedValueException;

use function Hyperf\Support\env;

class JwtService implements JwtServiceInterface
{
    public static function generateToken()
    {
        $payload = [
            'iss' => env('APP_NAME'),
            'iat' => time(),
            'exp' => time() + env('JWT_EXPIRE', 3600),
            'sub' => 1,
        ];
        return JWT::encode($payload, env('JWT_SECRET'), env('JWT_ALGORITHM'));
    }
}
