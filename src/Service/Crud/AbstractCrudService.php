<?php

namespace App\Service\Crud;

use App\Interfaces\CrudServiceInterface;
use App\Interfaces\DTOInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Abstract class for generic CRUD operations.
 *
 * This class provides basic implementations for common CRUD operations
 * using Doctrine ORM, allowing specific services to extend its functionality
 * for different entities.
 *
 * @template T
 */
abstract class AbstractCrudService implements CrudServiceInterface
{
    /** @var string Fully qualified class name of the managed entity. */
    protected string $className = '';

    /** @var EntityManagerInterface Doctrine's entity manager. */
    protected EntityManagerInterface $em;

    /** @const string Default error message for object not found. */
    protected const NOT_FOUND_MESSAGE = "Can't find the object.";

    /**
     * Class constructor.
     *
     * @param EntityManagerInterface $em entity manager
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Creates a new object from a DTO.
     *
     * @param DTOInterface $dto data transfer object
     *
     * @return T the created object
     */
    abstract public function create(DTOInterface $dto);

    /**
     * Updates an existing object based on the ID and DTO data.
     *
     * @param T            $existingEntity the entity that musb be updated
     * @param DTOInterface $dto            updated data transfer object
     *
     * @return T the updated object
     */
    abstract public function update($existingEntity, DTOInterface $dto);

    /**
     * Removes an object based on the ID.
     *
     * @param int $id object identifier
     *
     * @throws \InvalidArgumentException if the class name is not set
     * @throws NotFoundHttpException     if the object is not found
     */
    public function delete(int $id)
    {
        if (empty($this->className)) {
            throw new \InvalidArgumentException("Class name isn't setted");
        }

        $object = $this->em->getRepository($this->className)->find($id);
        if (is_null($object)) {
            throw new NotFoundHttpException(self::NOT_FOUND_MESSAGE);
        }

        $this->em->remove($object);
        $this->em->flush();
    }

    /**
     * Returns an object based on the ID.
     *
     * @param int $id object identifier
     *
     * @return mixed the found object
     *
     * @throws \InvalidArgumentException if the class name is not set
     * @throws NotFoundHttpException     if the object is not found
     */
    public function find(int $id)
    {
        if (empty($this->className)) {
            throw new \InvalidArgumentException("Class name isn't setted");
        }

        $object = $this->em->getRepository($this->className)->find($id);
        if (is_null($object)) {
            throw new NotFoundHttpException(self::NOT_FOUND_MESSAGE);
        }

        return $object;
    }

    /**
     * Returns all objects of the managed entity.
     *
     * @return array list of found objects
     *
     * @throws \InvalidArgumentException if the class name is not set
     */
    public function findAll()
    {
        if (empty($this->className)) {
            throw new \InvalidArgumentException("Class name isn't setted");
        }

        return $this->em->getRepository($this->className)->findAll();
    }
}
