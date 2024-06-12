<?php
class shopCatlinkPluginCategoryAddController extends waJsonController
{
	public function execute()
	{
		$product_id = waRequest::post('productId', null);
        $category_id = waRequest::post('categoryId', null);
		$name = waRequest::post('name', null);

		$data = array('product_id' => $product_id, 'category_id' => $category_id, 'name' => $name);

		$catlink = waSystem::getInstance('shop')->getPlugin('catlink');
		$this->response = $catlink->addCategory($data);
	}
}