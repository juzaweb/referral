<?php
namespace Juzaweb\Modules\Core\Tests\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Banned = 'banned';

    public const ACTIVE = 'active';
    public const BANNED = 'banned';
}
