<?php
class shopCatlinkPluginProductUpdateController extends waJsonController
{
	public function execute()
	{
		$product_id = waRequest::post('productId', null);
        $category_id = waRequest::post('categoryId', null);
        $is_checked = waRequest::post('isChecked', 0);

		$data = array('product_id' => $product_id, 'category_id' => $category_id, 'is_checked' => $is_checked);

		$catlink = waSystem::getInstance('shop')->getPlugin('catlink');
		$this->response = $catlink->updateCategory($data);
		
	}
}