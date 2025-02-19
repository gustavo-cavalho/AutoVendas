<?php

namespace App\Controller;

use App\DTO\AdDTO;
use App\Entity\Ad;
use App\Entity\Vehicle;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Exceptions\ValidationException;
use App\Interfaces\CrudServiceInterface;
use App\Security\Voter\AdVoter;
use App\Security\Voter\StoreVoter;
use App\Service\Api\FipeApiService;
use App\Service\Crud\AdService;
use App\Service\Crud\VehicleStoreService;
use App\Traits\Util\JsonRequestUtil;
use App\Traits\Util\JsonResponseUtil;
use App\Traits\Util\SerializerUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AdController extends AbstractController
{
    use JsonRequestUtil;
    use JsonResponseUtil;
    use SerializerUtil;

    private CrudServiceInterface $crudService;
    private CrudServiceInterface $storeFinder;
    private FipeApiService $fipeApi;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;

    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        HttpClientInterface $httpClient
    ) {
        $this->crudService = new AdService($em);
        $this->storeFinder = new VehicleStoreService($em);
        $this->fipeApi = new FipeApiService($httpClient);
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/store/{storeId}/vehicle/{vehicleId}/ad", name="advertise_vehicle", methods={"POST"})
     */
    public function advertise(Request $request, int $storeId, int $vehicleId): JsonResponse
    {
        try {
            $store = $this->storeFinder->find($storeId);
            $this->denyAccessUnlessGranted(StoreVoter::ACCESS, $store);

            $data = $this->getJsonBodyFields($request, ['description', 'price', 'status']);
            $adDTO = $this->newAdDTO($data, $storeId, $vehicleId);

            $adDTO->validate($this->validator, []);

            $ad = $this->crudService->create($adDTO);

            $this->serialize($ad, [Ad::SERIALIZE_SHOW]);

            return $this->statusCreated('Ad created!', $ad);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
        } catch (IdentityAlreadyExistsException $e) {
            return $this->errConflict($e->getMessage());
        } catch (AccessDeniedException $e) {
            return $this->errForbidden($e->getMessage());
        }
    }

    /**
     * @Route("/ad/{id}", name="update_ad", methods={"PUT"})
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $ad = $this->crudService->find($id);
            $this->denyAccessUnlessGranted(AdVoter::EDIT, $ad);

            $data = $this->getJsonBodyFields($request, ['description', 'price', 'status']);
            $adDTO = $this->newAdDTO($data, 0, 0);

            $adDTO->validate($this->validator, []);

            $ad = $this->crudService->update($id, $adDTO);

            $this->serialize($ad, Ad::SERIALIZE_SHOW);

            return $this->statusCreated('Ad updated!', $ad);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
        } catch (NotFoundHttpException $e) {
            return $this->errNotFound($e->getMessage());
        } catch (AccessDeniedException $e) {
            return $this->errForbidden($e->getMessage());
        }
    }

    /**
     * @Route("/ad/{id}", name="show_ad", methods={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        try {
            /** @var Ad $ad */
            $ad = $this->crudService->find($id);
            $this->denyAccessUnlessGranted(AdVoter::VIEW, $ad);

            $fipeData = $this->fipeApi->getInfoFromFipe($ad->getVehicleAdvertised());
            $ad = $this->serialize($ad, [Ad::SERIALIZE_SHOW]);

            // TODO; improve data visualization
            return $this->statusOk('Founded the ad', [
                'fipeData' => $fipeData,
                'ad' => $ad,
            ]);
        } catch (NotFoundHttpException $e) {
            return $this->errNotFound($e->getMessage());
        } catch (AccessDeniedException $e) {
            return $this->errForbidden($e->getMessage());
        }
    }

    /**
     * @Route("/ad", name="index_ad", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $ads = $this->crudService->findAll();
        $ads = $this->serialize($ads, [Ad::SERIALIZE_INDEX]);

        return $this->statusOk('There is all ads.', $ads);
    }

    /**
     * @Route("/ad/{id}", name="delete_ad", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        try {
            /** @var Ad $ad */
            $ad = $this->crudService->find($id);
            $this->denyAccessUnlessGranted(StoreVoter::ACCESS, $ad->getAdvertiserStore());

            $this->crudService->delete($id);
        } catch (AccessDeniedException $e) {
            return $this->errForbidden($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errNotFound($e->getMessage());
        }
    }

    private function newAdDTO(array $data, int $storeId, int $vehicleId): AdDTO
    {
        return new AdDTO(
            $vehicleId,
            $storeId,
            $data['price'],
            $data['status'],
            $data['description']
        );
    }
}
