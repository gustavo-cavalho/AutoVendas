<?php

namespace App\Security\Voter;

use App\Entity\Vehicle;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class VehicleVoter extends Voter
{
    public const EDIT = 'VEHICLE:EDIT';
    public const VIEW = 'VEHICLE:VIEW';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Vehicle;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Vehicle $vehicle */
        $vehicle = $subject;
        $userIsEmployer = $vehicle->getVehicleStore()->getEmployers()->contains($user);

        switch ($attribute) {
            case self::EDIT:
            case self::VIEW:
                return $userIsEmployer;
        }

        return false;
    }
}
