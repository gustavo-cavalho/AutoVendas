<?php

namespace App\Controller;

use App\DTO\AddressDTO;
use App\DTO\VehicleStoreDTO;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Exceptions\ValidationException;
use App\Interfaces\CrudServiceInterface;
use App\Repository\VehicleStoreRepository;
use App\Service\VehicleStoreService;
use App\Traits\Util\JsonRequestUtil;
use App\Traits\Util\JsonResponseUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * PEGAR UMA:
 * PEGAR TODAS:
 */
class VehicleStoreController extends AbstractController
{
    use JsonRequestUtil;
    use JsonResponseUtil;

    private ValidatorInterface $validator;
    private CrudServiceInterface $crudService;

    public function __construct(ValidatorInterface $validator, VehicleStoreRepository $repository)
    {
        $this->validator = $validator;
        $this->crudService = new VehicleStoreService($repository);
    }

    /**
     * @Route("/store", name="register_store", method={"POST"})
     */
    public function register(Request $request): JsonResponse
    {
        // TODO: Check permission

        try {
            $vehicleStoreData = $this->getJsonBodyFields($request, ['credencial', 'phone', 'email', 'name']);
            $addressData = $this->getJsonBodyFields($request, ['cep', 'street', 'number', 'neighborhood', 'city', 'state', 'complement']);

            $addressDTO = $this->newAddressDTO($addressData);
            $vehicleStoreDTO = $this->newVehicleStoreDTO($vehicleStoreData, $addressDTO);

            $vehicleStoreDTO->validate($this->validator, []);

            $vehicleStore = $this->crudService->create($vehicleStoreDTO);

            return $this->statusCreated('Store created!', $vehicleStore);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
        } catch (IdentityAlreadyExistsException $e) {
            return $this->errConflict($e->getMessage());
        }
    }

    /**
     * @Route("/store/{id}", name="update_store", method={"PUT"})
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $vehicleStoreData = $this->getJsonBodyFields($request, ['credencial', 'phone', 'email', 'name']);
            $addressData = $this->getJsonBodyFields($request, ['cep', 'street', 'number', 'neighborhood', 'city', 'state', 'complement']);

            $addressDTO = $this->newAddressDTO($addressData);
            $vehicleStoreDTO = $this->newVehicleStoreDTO($vehicleStoreData, $addressDTO);

            $vehicleStoreDTO->validate($this->validator, []);

            $vehicleStore = $this->crudService->update($id, $vehicleStoreDTO);

            return $this->statusCreated('Store updated!', $vehicleStore);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
        } catch (NotFoundHttpException $e) {
            return $this->errNotFound($e->getMessage());
        }
    }

    /**
     * @Route("/store", name="index_store", method={"GET"})
     */
    public function index(): JsonResponse
    {
        $vehicleStores = $this->crudService->findAll();

        return $this->statusOk('Get all stores.', $vehicleStores);
    }

    /**
     * @Route("/store/{id}", name="show_soter", method={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        try {
            $vehicleStore = $this->crudService->find($id);

            return $this->statusOk('Founded the store!', $vehicleStore);
        } catch (NotFoundHttpException $e) {
            return $this->errNotFound($e->getMessage());
        }
    }

    private function newAddressDTO(array $addressData): AddressDTO
    {
        return new AddressDTO(
            $addressData['cep'],
            $addressData['street'],
            $addressData['number'],
            $addressData['neighborhood'],
            $addressData['city'],
            $addressData['state'],
            $addressData['complement']
        );
    }

    private function newVehicleStoreDTO(array $vehicleStoreData, AddressDTO $addressDTO): VehicleStoreDTO
    {
        return new VehicleStoreDTO(
            $vehicleStoreData['credencial'],
            $vehicleStoreData['phone'],
            $vehicleStoreData['email'],
            $vehicleStoreData['name'],
            $addressDTO
        );
    }
}
