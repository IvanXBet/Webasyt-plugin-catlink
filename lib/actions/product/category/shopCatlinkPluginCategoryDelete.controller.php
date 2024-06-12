<?php
class shopCatlinkPluginCategoryDeleteController extends waJsonController
{
	public function execute()
	{
		$product_id = waRequest::post('productId', null);
        $category_id = waRequest::post('categoryId', null);

		if (!$category_id) {
            $this->response = array('result' => 0, 'message' => 'ID категории не указан');
            return;
        }

		$data = array('product_id' => $product_id, 'category_id' => $category_id);

		$catlink = waSystem::getInstance('shop')->getPlugin('catlink');
		$this->response = $catlink->deleteCategory($data);
	}
}