<?php

declare(strict_types=1);

namespace M2\CRUD\Api;

use M2\CRUD\Api\Data\ItemInterface;
use M2\CRUD\Api\Data\ItemSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Item repository service contract.
 *
 * Changed: getList() returns the typed ItemSearchResultsInterface, parameters are named after the entity, and the
 * documented exceptions match what the implementation throws.
 *
 * @api
 */
interface ItemRepositoryInterface
{
    /**
     * Create or update an item.
     *
     * @param ItemInterface $item
     * @return ItemInterface
     * @throws CouldNotSaveException
     */
    public function save(ItemInterface $item): ItemInterface;

    /**
     * Get an item by id.
     *
     * @param int $id
     * @return ItemInterface
     * @throws NoSuchEntityException If the item does not exist.
     */
    public function getById(int $id): ItemInterface;

    /**
     * Retrieve the items matching the criteria.
     *
     * @param SearchCriteriaInterface $criteria
     * @return ItemSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): ItemSearchResultsInterface;

    /**
     * Delete an item.
     *
     * @param ItemInterface $item
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ItemInterface $item): bool;

    /**
     * Delete an item by id.
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException If the item does not exist.
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $id): bool;
}
