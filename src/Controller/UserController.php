<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\VehicleStore;
use App\Interfaces\CrudServiceInterface;
use App\Service\Crud\VehicleStoreService;
use App\Traits\Util\JsonRequestUtil;
use App\Traits\Util\JsonResponseUtil;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Temporary until find a better solution.
 */
class UserController extends AbstractController
{
    use JsonRequestUtil;
    use JsonResponseUtil;

    private CrudServiceInterface $storeService;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->storeService = new VehicleStoreService($em);
    }

    /**
     * @Route("/store/{id}/user", name="add_permission_to_user", methods={"POST"})
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function addPermission(int $id): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('User not autenticate.');
        }

        /** @var VehicleStore $store */
        $store = $this->storeService->find($id);
        if (!$store) {
            throw $this->createNotFoundException('Store not found.');
        }

        $store->addEmployer($user);
        $this->em->flush();

        return $this->statusOk('Employer added!');
    }

    /**
     * @Route("/user/to-admin", name="user_to_admin", methods={"POST"})
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function toAdmin(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Usuário não autenticado.');
        }

        if (in_array(User::ROLE_ADMIN, $user->getRoles(), true)) {
            return $this->statusOk('User already is a admin.');
        }

        $user->setRoles([User::ROLE_ADMIN]);
        $this->em->flush();

        return $this->statusOk('You\'re now a admin.');
    }
}
