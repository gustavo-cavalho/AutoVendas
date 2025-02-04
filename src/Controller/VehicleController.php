<?php

namespace App\Controller;

use App\DTO\VehicleDTO;
use App\Entity\Vehicle;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Exceptions\ValidationException;
use App\Interfaces\CrudServiceInterface;
use App\Security\Voter\VehicleVoter;
use App\Service\Api\FipeApiService;
use App\Service\Crud\VehicleService;
use App\Traits\Util\JsonRequestUtil;
use App\Traits\Util\JsonResponseUtil;
use App\Traits\Util\SerializerUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class VehicleController extends AbstractController
{
    use JsonRequestUtil;
    use JsonResponseUtil;
    use SerializerUtil;

    private CrudServiceInterface $crudService;
    private ValidatorInterface $validator;
    private FipeApiService $fipeApi;
    private SerializerInterface $serializer;

    public function __construct(
        EntityManagerInterface $em,
        HttpClientInterface $httpClient,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ) {
        $this->crudService = new VehicleService($em);
        $this->validator = $validator;
        $this->fipeApi = new FipeApiService($httpClient);
        $this->serializer = $serializer;
    }

    /**
     * @Route("store/{id}/vehicle", name="register_vehicle", methods={"POST"})
     */
    public function register(Request $request, int $id): JsonResponse
    {
        try {
            // TODO: check if the users is a employer of the store

            $data = $this->getJsonBodyFields($request, ['type', 'brand', 'model', 'year', 'mileage', 'plate']);

            $vehicleDTO = $this->newVehicleDTO($id, $data);

            $vehicleDTO->validate($this->validator, []);
            $this->fipeApi->validate($vehicleDTO);

            $vehicle = $this->crudService->create($vehicleDTO);

            $vehicle = $this->serialize($vehicle, [Vehicle::SERIALIZE_SHOW]);

            return $this->statusCreated('Vehicle registered sucefully!', $vehicle);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
        } catch (IdentityAlreadyExistsException $e) {
            return $this->errConflict($e->getMessage());
        }
    }

    /**
     * @Route("store/{storeId}/vehicle/{id}", name="update_vehicle", methods={"PUT"})
     */
    public function update(Request $request, int $storeId, int $id): JsonResponse
    {
        try {
            $vehicle = $this->crudService->find($id);
            $this->denyAccessUnlessGranted(VehicleVoter::EDIT, $vehicle);

            $data = $this->getJsonBodyFields($request, ['type', 'brand', 'model', 'year', 'mileage', 'plate']);

            $vehicleDTO = $this->newVehicleDTO($storeId, $data);

            $vehicleDTO->validate($this->validator, []);
            $this->fipeApi->validate($vehicleDTO);

            $vehicle = $this->crudService->update($vehicle, $vehicleDTO);

            $vehicle = $this->serialize($vehicle, [Vehicle::SERIALIZE_SHOW]);

            return $this->statusOk('Updated!', $vehicle);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
        } catch (NotFoundHttpException $e) {
            return $this->errNotFound($e->getMessage());
        }
    }

    /**
     * @Route("store/{storeId}/vehicle/{id}", name="show_vehicle", methods={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        try {
            $vehicle = $this->crudService->find($id);
            $this->denyAccessUnlessGranted(VehicleVoter::VIEW, $vehicle);

            $vehicle = $this->serialize($vehicle, [Vehicle::SERIALIZE_SHOW]);

            return $this->statusOk('Vehicle founded!', $vehicle);
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
        } catch (NotFoundHttpException $e) {
            return $this->errNotFound($e->getMessage());
        } catch (AccessDeniedException $e) {
            return $this->errForbidden($e->getMessage());
        }
    }

    private function newVehicleDTO(int $id, array $data): VehicleDTO
    {
        return new VehicleDTO(
            $id,
            $data['type'],
            $data['brand'],
            $data['model'],
            $data['year'],
            $data['mileage'],
            $data['plate']
        );
    }
}
