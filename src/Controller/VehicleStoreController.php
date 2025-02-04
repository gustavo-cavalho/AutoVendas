<?php

namespace App\Controller;

use App\DTO\AddressDTO;
use App\DTO\VehicleStoreDTO;
use App\Entity\User;
use App\Entity\VehicleStore;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Exceptions\ValidationException;
use App\Interfaces\CrudServiceInterface;
use App\Security\Voter\StoreVoter;
use App\Service\Crud\VehicleStoreService;
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

class VehicleStoreController extends AbstractController
{
    use JsonRequestUtil;
    use JsonResponseUtil;
    use SerializerUtil;

    private ValidatorInterface $validator;
    private CrudServiceInterface $crudService;
    private SerializerInterface $serializer;

    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $em,
        SerializerInterface $serializer
    ) {
        $this->validator = $validator;
        $this->crudService = new VehicleStoreService($em);
        $this->serializer = $serializer;
    }

    /**
     * @Route("/store", name="register_store", methods={"POST"})
     */
    public function register(Request $request)
    {
        try {
            $this->denyAccessUnlessGranted(User::ROLE_ADMIN);

            $vehicleStoreData = $this->getJsonBodyFields($request, ['credencial', 'phone', 'email', 'name']);
            $addressData = $this->getJsonBodyFields($request, ['cep', 'street', 'number', 'neighborhood', 'city', 'state', 'complement']);

            $addressDTO = $this->newAddressDTO($addressData);
            $vehicleStoreDTO = $this->newVehicleStoreDTO($vehicleStoreData, $addressDTO);

            $vehicleStoreDTO->validate($this->validator, []);

            $vehicleStore = $this->crudService->create($vehicleStoreDTO);

            $vehicleStore = $this->serialize($vehicleStore, [VehicleStore::SERIALIZE_SHOW]);

            return $this->statusCreated('Store created!', $vehicleStore);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
        } catch (IdentityAlreadyExistsException $e) {
            return $this->errConflict($e->getMessage());
        } catch (AccessDeniedException $e) {
            return $this->errForbidden($e->getMessage());
        } catch (\Exception $e) {
            return $this->errInteralServer('Sorry, but some error just ocurred. :(');
        }
    }

    /**
     * @Route("/store/{id}", name="update_store", methods={"PUT"})
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            /** @var VehicleStore $store */
            $store = $this->crudService->find($id);
            $this->denyAccessUnlessGranted(StoreVoter::EDIT, $store);

            $vehicleStoreData = $this->getJsonBodyFields($request, ['credencial', 'phone', 'email', 'name']);
            $addressData = $this->getJsonBodyFields($request, ['cep', 'street', 'number', 'neighborhood', 'city', 'state', 'complement']);

            $addressDTO = $this->newAddressDTO($addressData);
            $vehicleStoreDTO = $this->newVehicleStoreDTO($vehicleStoreData, $addressDTO);

            $vehicleStoreDTO->validate($this->validator, []);

            $vehicleStore = $this->crudService->update($store, $vehicleStoreDTO);

            $vehicleStore = $this->serialize($vehicleStore, [VehicleStore::SERIALIZE_SHOW]);

            return $this->statusCreated('Store updated!', $vehicleStore);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
        } catch (NotFoundHttpException $e) {
            return $this->errNotFound($e->getMessage());
        } catch (AccessDeniedException $e) {
            return $this->errForbidden($e->getMessage());
        } catch (\Exception $e) {
            return $this->errInteralServer('Sorry, but some error just ocurred. :(');
        }
    }

    /**
     * @Route("/store", name="index_store", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $vehicleStores = $this->crudService->findAll();

        $vehicleStores = $this->serialize($vehicleStores, [VehicleStore::SERIALIZE_INDEX]);

        return $this->statusOk('Get all stores.', $vehicleStores);
    }

    /**
     * @Route("/store/{id}", name="show_store", methods={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        try {
            // TODO: check if user works of the store

            $vehicleStore = $this->crudService->find($id);

            $vehicleStore = $this->serialize($vehicleStore, [VehicleStore::SERIALIZE_SHOW]);

            return $this->statusOk('Founded the store!', $vehicleStore);
        } catch (NotFoundHttpException $e) {
            return $this->errNotFound($e->getMessage());
        } catch (\Exception $e) {
            return $this->errInteralServer('Sorry, but some error just ocurred. :(');
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
