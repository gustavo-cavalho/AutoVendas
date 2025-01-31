<?php

namespace App\Interfaces;

/**
 * Generic interface that operates CRUD actions.
 *
 * @template T representes the type of the entity that will be returned
 */
interface CrudServiceInterface
{
    /**
     * Creates a Entity based on the passed DTO.
     *
     * @param DTOInterface $dto the payload the method must receive
     *
     * @return T thats is based on the DTO type
     *
     * @throws IdentityAlreadyExistsExceptions if a Entity with a unique key exists
     */
    public function create(DTOInterface $dto);

    /**
     * Update a entity with the passe ID with the content of the DTO.
     *
     * @param int          $id  for the Entity that will be uptaded
     * @param DTOInterface $dto the content that will be used to update
     *
     * @return T with the new data
     *
     * @throws NotFoundHtppException if can't find the Entity that will be updated
     */
    public function update(int $id, DTOInterface $dto);

    /**
     * Delete a Entity that contains the passed ID.
     *
     * @param int $id of the Entity that will be deleted
     *
     * @return void only deletes the Entity
     *
     * @throws NotFoundHttpException if can't find the Entity that will be deleted
     */
    public function delete(int $id);

    /**
     * Uses a ID to get a specifi Entity.
     *
     * @param int $id of the entity that will be showed
     *
     * @return T the entity founded
     *
     * @throws NotFoundHttpException if no entity is founded with the given ID
     */
    public function find(int $id);

    /**
     * Return all entities.
     *
     * @return T[] an array of the entities
     */
    public function findAll();
}
