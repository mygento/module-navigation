define([
  'Magento_Catalog/js/components/new-category',
  'Magento_Catalog/js/components/visible-on-option/strategy'
], function (NewCategory, strategy) {
  'use strict';

  return NewCategory.extend(strategy);
});
