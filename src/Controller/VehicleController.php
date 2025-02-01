<?php

namespace App\Controller;

use App\DTO\VehicleDTO;
use App\Exceptions\ValidationException;
use App\Interfaces\CrudServiceInterface;
use App\Repository\VehicleRepository;
use App\Service\VehicleService;
use App\Traits\Util\JsonRequestUtil;
use App\Traits\Util\JsonResponseUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class VehicleController extends AbstractController
{
    use JsonRequestUtil;
    use JsonResponseUtil;

    private CrudServiceInterface $service;
    private ValidatorInterface $validator;

    public function __construct(VehicleRepository $repository, HttpClientInterface $httpClient, ValidatorInterface $validator)
    {
        $this->service = new VehicleService($repository, $httpClient);
        $this->validator = $validator;
    }

    /**
     * @Route("store/{id}/vehicle", name="register_vehicle", methods={"POST"})
     */
    public function register(Request $request, int $id): JsonResponse
    {
        try {
            $data = $this->getJsonBodyFields($request, ['type', 'brand', 'model', 'year', 'mileage', 'plate']);

            $vehicleDTO = $this->newVehicleDTO($id, $data);

            $vehicleDTO->validate($this->validator, []);

            $vehicle = $this->service->create($vehicleDTO);

            //  TODO: setialize

            return $this->statusCreated('Vehicle registered sucefully!', $vehicle);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
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
