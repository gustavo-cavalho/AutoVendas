<?php

namespace App\Controller;

use App\DTO\AdDTO;
use App\Exceptions\ValidationException;
use App\Interfaces\CrudServiceInterface;
use App\Service\Crud\AdService;
use App\Traits\Util\JsonRequestUtil;
use App\Traits\Util\JsonResponseUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdController extends AbstractController
{
    use JsonRequestUtil;
    use JsonResponseUtil;

    private ValidatorInterface $validator;
    private CrudServiceInterface $crudService;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $this->validator = $validator;
        $this->crudService = new AdService($em);
    }

    /**
     * @Route("/store/{storeID}/vehicle/{vehicleId}/ad", name="advertise_vehicle", methods={"POST"})
     */
    public function advertise(Request $request, int $storeId, int $vehicleId): JsonResponse
    {
        try {
            $data = $this->getJsonBodyFields($request, ['description', 'price', 'status']);

            $adDTO = $this->newAdDTO($data, $storeId, $vehicleId);

            $adDTO->validate($this->validator, []);

            $ad = $this->crudService->create($adDTO);

            // $this->serializer->serialize($ad, Ad::SERIALIZE_SHOW);

            return $this->statusCreated('Ad created!', $ad);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
        }
    }

    private function newAdDTO(array $data, int $storeId, int $vehicleId): AdDTO
    {
        return new AdDTO(
            $data['description'],
            $data['price'],
            $data['status'],
            $storeId,
            $vehicleId
        );
    }
}
