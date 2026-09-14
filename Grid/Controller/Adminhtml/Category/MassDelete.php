<?php

declare(strict_types=1);

namespace Dev\Grid\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Throwable;

/**
 * Mass delete action of the category listing example grid.
 *
 * Changed:
 * - HttpPostActionInterface replaces the manual isPost() check, so the router rejects other methods;
 * - CategoryRepositoryInterface is a required constructor dependency instead of an ObjectManager fallback;
 * - the tree root and the store root categories (level 0 and 1) are skipped. "Select all" in the grid is not
 *   limited to the listed rows, so the example could delete every category including the roots, which breaks
 *   the catalog of every store;
 * - one failing category no longer stops the loop and shows a raw exception.
 */
class MassDelete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Magento_Catalog::categories';

    /**
     * Categories below this tree level are the tree root (0) and the store root categories (1).
     */
    private const MIN_DELETABLE_LEVEL = 2;

    /**
     * @param Action\Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        Action\Context $context,
        private readonly Filter $filter,
        private readonly CollectionFactory $collectionFactory,
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute(): ResultInterface
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $categoryDeleted = 0;
        $categorySkipped = 0;

        /** @var Category $category */
        foreach ($collection->getItems() as $category) {
            if ((int)$category->getLevel() < self::MIN_DELETABLE_LEVEL) {
                $categorySkipped++;
                continue;
            }

            try {
                $this->categoryRepository->delete($category);
                $categoryDeleted++;
            } catch (Throwable $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('The category with ID %1 could not be deleted.', $category->getId())
                );
            }
        }

        if ($categoryDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $categoryDeleted)
            );
        }

        if ($categorySkipped) {
            $this->messageManager->addWarningMessage(
                __('%1 root categories were skipped, they cannot be deleted here.', $categorySkipped)
            );
        }

        return $this->resultFactory
            ->create(ResultFactory::TYPE_REDIRECT)
            ->setPath('dev_grid/index/index');
    }
}
