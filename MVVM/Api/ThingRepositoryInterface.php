<?php

declare(strict_types=1);

namespace M2\MVVM\Api;

use M2\MVVM\Api\Data\ThingInterface;
use M2\MVVM\Api\Data\ThingSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * A repository defines the common CRUD operations (create, read, update, delete) for an entity.
 *
 * Changed: getList() declared an array return type while it returns a search results object (TypeError); it now
 * returns ThingSearchResultsInterface.
 *
 * @api
 */
interface ThingRepositoryInterface
{
    /**
     * Creates a new record when there is no id, otherwise updates the record with that id.
     *
     * @param ThingInterface $thing
     * @return ThingInterface
     * @throws CouldNotSaveException
     */
    public function save(ThingInterface $thing): ThingInterface;

    /**
     * Get by id
     *
     * @param int $id
     * @return ThingInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): ThingInterface;

    /**
     * Returns the things matching the search criteria.
     *
     * @param SearchCriteriaInterface $criteria
     * @return ThingSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): ThingSearchResultsInterface;

    /**
     * Delete
     *
     * @param ThingInterface $thing
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ThingInterface $thing): bool;

    /**
     * Delete by id
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $id): bool;
}
