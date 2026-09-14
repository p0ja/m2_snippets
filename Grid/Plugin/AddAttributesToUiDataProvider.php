<?php

declare(strict_types=1);

namespace Dev\Grid\Plugin;

use Dev\Grid\Ui\DataProvider\Category\ListingDataProvider as CategoryDataProvider;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class AddAttributesToUiDataProvider
{
    /**
     * @param AttributeRepositoryInterface $attributeRepository
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(
        private readonly AttributeRepositoryInterface $attributeRepository,
        private readonly ProductMetadataInterface $productMetadata
    ) {
    }

    /**
     * @param CategoryDataProvider $subject
     * @param SearchResult $result
     * @return SearchResult
     */
    public function afterGetSearchResult(CategoryDataProvider $subject, SearchResult $result): SearchResult
    {
        if ($result->isLoaded()) {
            return $result;
        }

        $edition = $this->productMetadata->getEdition();

        $column = 'entity_id';
        if ($edition == 'Enterprise') {
            $column = 'row_id';
        }

        $attribute = $this->attributeRepository->get('catalog_category', 'name');

        // Changed: the join condition and the filter are built with quoteIdentifier() and quoteInto() instead of
        // string concatenation. The values are not user input today, but concatenated SQL becomes an injection
        // point as soon as someone copies the snippet with a request value. The double-quoted "B%" literal also
        // broke on MySQL servers running with ANSI_QUOTES.
        $connection = $result->getConnection();
        $joinCondition = sprintf(
            '%s = %s AND %s',
            $connection->quoteIdentifier('devgridname.' . $column),
            $connection->quoteIdentifier('main_table.' . $column),
            $connection->quoteInto('devgridname.attribute_id = ?', (int)$attribute->getAttributeId())
        );

        $result->getSelect()->joinLeft(
            ['devgridname' => $attribute->getBackendTable()],
            $joinCondition,
            ['name' => 'devgridname.value']
        );

        $result->getSelect()->where('devgridname.value LIKE ?', 'B%');

        return $result;
    }
}
