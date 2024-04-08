<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\database;

use RuntimeException;

class BinaryStringParser{
    public const TYPE_MYSQL = 0;
    public const TYPE_SQLITE = 1;

    public function __construct(
        private readonly int $type = self::TYPE_MYSQL
    ){}

    public function encode(string $data) : string{
        return ($this->type === self::TYPE_MYSQL) ? $data : bin2hex($data);
    }

    public function decode(string $data) : string{
        return ($this->type === self::TYPE_MYSQL) ? $data : hex2bin($data);
    }

    public static function fromDatabase(string $type) : BinaryStringParser{
        return match($type){
            "mysql" => new self(self::TYPE_MYSQL),
            "sqlite" => new self(self::TYPE_SQLITE),
            default => throw new RuntimeException("Unsupported database: {$type}")
        };
    }
}