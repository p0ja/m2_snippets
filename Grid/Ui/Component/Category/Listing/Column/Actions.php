<?php

declare(strict_types=1);

namespace Dev\Grid\Ui\Component\Category\Listing\Column;

use Magento\Framework\Url;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Actions column of the category listing: a link to the category page on the storefront.
 *
 * Changed: prepareDataSource() called the undefined $this->_urlBuilder (fatal error); it now uses the injected
 * frontend URL builder. The context and component factory are passed to the parent instead of being redeclared
 * as promoted properties, which shadowed the parent's properties.
 */
class Actions extends Column
{
    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Url $urlBuilder frontend URL builder, admin URLs would not open the storefront
     * @param string $viewUrl
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        private readonly Url $urlBuilder,
        private readonly string $viewUrl = '',
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $name = $this->getData('name');
        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['entity_id'])) {
                continue;
            }

            $item[$name]['view'] = [
                'href' => $this->urlBuilder->getUrl($this->viewUrl, ['id' => $item['entity_id']]),
                'target' => '_blank',
                'label' => __('View on Frontend'),
            ];
        }

        return $dataSource;
    }
}
