<?php

namespace App\Security\Voter;

use App\Entity\Ad;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AdVoter extends Voter
{
    public const EDIT = 'AD:EDIT';
    public const VIEW = 'AD:VIEW';
    public const CREATE = 'AD:CREATE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Ad;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Ad $ad */
        $ad = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($user, $ad);
            case self::VIEW:
                return $this->canView($user, $ad);
        }

        return false;
    }

    private function canEdit(User $user, Ad $ad): bool
    {
        $userStore = $user->getVehicleStore();
        $adStore = $ad->getAdvertiserStore();

        /*
         * Check if the store that user works
         * is the same sorte the is making the ad
         */
        return $userStore === $adStore;
    }

    private function canView(User $user, Ad $ad): bool
    {
        if ($this->canEdit($user, $ad)) {
            return true;
        }

        return $ad->getStatus() === Ad::STATUS_ON_SALE;
    }
}
