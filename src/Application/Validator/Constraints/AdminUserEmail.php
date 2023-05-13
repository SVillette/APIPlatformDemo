<?php

declare(strict_types=1);

namespace App\Application\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class AdminUserEmail extends Constraint
{
    public string $message = 'app.admin_user_email';
}
