<?php

declare(strict_types=1);

namespace M2\MVVM\Ui\Component\Listing\Column\M2mvvmthings;

use M2\MVVM\Api\Data\ThingInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class PageActions extends Column
{
    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource): array
    {
        // Changed: the route id is lowercase like etc/adminhtml/routes.xml ("M2_mvvm_things" did not match on
        // case-sensitive routers), and rows without an id get no link instead of an edit URL for the id "X".

        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $name = $this->getData('name');
        foreach ($dataSource['data']['items'] as &$item) {
            if (empty($item[ThingInterface::THING_ID])) {
                continue;
            }

            $item[$name]['edit'] = [
                'href' => $this->getContext()->getUrl(
                    'm2_mvvm_things/thing/edit',
                    ['thing_id' => $item[ThingInterface::THING_ID]]
                ),
                'label' => __('Edit'),
            ];
        }

        return $dataSource;
    }
}
