<?php

declare(strict_types=1);

namespace App\Core\Service;

use App\Core\Repository\UserRepository;
use Micoli\Smtp\Server\Authentication\AuthenticationDataInterface;
use Micoli\Smtp\Server\Authentication\AuthenticationMethodInterface;
use Micoli\Smtp\Server\Authentication\IdentityValidatorInterface;

final class IdentityValidator implements IdentityValidatorInterface
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function checkAuth(AuthenticationMethodInterface $method, ?AuthenticationDataInterface $authenticationData): bool
    {
        $user = $this->userRepository->getByUserName($authenticationData->getUsername());
        if (null === $user) {
            return false;
        }

        return $method->validateIdentity($authenticationData, $user->getPassword());
    }
}
