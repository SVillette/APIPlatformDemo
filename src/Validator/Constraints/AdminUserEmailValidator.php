<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Domain\Repository\AdminUserRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Webmozart\Assert\Assert;

final class AdminUserEmailValidator extends ConstraintValidator
{
    public function __construct(private readonly AdminUserRepositoryInterface $adminUserRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof AdminUserEmail) {
            throw new UnexpectedTypeException($constraint, AdminUserEmail::class);
        }

        if (null === $value) {
            return;
        }

        Assert::string($value);

        $adminUser = $this->adminUserRepository->findOneBy(['email' => $value]);

        if (null !== $adminUser) {
            return;
        }

        $this->context
            ->buildViolation($constraint->message, ['{{ email }}' => $value])
            ->addViolation()
        ;
    }
}
