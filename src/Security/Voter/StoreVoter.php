<?php

namespace App\Security\Voter;

use App\Entity\VehicleStore;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class StoreVoter extends Voter
{
    public const EDIT = 'STORE:EDIT';
    public const VIEW = 'STORE:VIEW';
    public const ACCESS = 'STORE:ACCESS';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof VehicleStore;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var VehicleStore $store */
        $store = $subject;
        $userIsEmployer = $store->getEmployers()->contains($user);

        switch ($attribute) {
            case self::EDIT:
            case self::VIEW:
            case self::ACCESS:
                return $userIsEmployer;
        }

        return false;
    }
}
