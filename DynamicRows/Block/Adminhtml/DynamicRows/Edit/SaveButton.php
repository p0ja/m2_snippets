<?php

declare(strict_types=1);

namespace Vendor\DynamicRows\Block\Adminhtml\DynamicRows\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\CatalogRule\Block\Adminhtml\Edit\GenericButton;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
   /**
    * Changed: the button submits the UI form (POST to the form's submit_url with the rows and the form key).
    * setLocation() opened the save URL with GET and without any row data, which made the save action delete
    * every row; the JavaScript string also had a syntax error ('').
    */
   public function getButtonData()
   {
       return [
           'label' => __('Save Rows'),
           'class' => 'save primary',
           'data_attribute' => [
               'mage-init' => ['button' => ['event' => 'save']],
               'form-role' => 'save',
           ],
           'sort_order' => 90,
       ];
   }
}
