<?php

declare(strict_types=1);

namespace Support\Admin\Domain\Account;

enum UserRole: string
{
    case Admin = 'admin';
    case Editor = 'editor';
    case Owner = 'owner';
}
