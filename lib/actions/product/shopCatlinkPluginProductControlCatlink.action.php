<?php


class shopCatlinkPluginProductControlCatlinkAction extends waViewAction
{
	public function execute()
	{
		$product_id = waRequest::get('id', 0, 'int');

		// Модели для работы с категориями и продуктами
		$category_model = new shopCategoryModel();
		$catlink_model = new shopCatlinkPluginProductCategoryModel();
		$product_category_model = new shopCategoryProductsModel();

		$categories = $category_model->getAll();

		$product_categories = $product_category_model->getByField('product_id', $product_id, true);
		$assigned_category_ids = array_map(function($category) {
			return $category['category_id'];
		}, $product_categories);

		$catlink_categories = $catlink_model->getByField('product_id', $product_id, true);
		$catlink_category_ids = array_map(function($category) {
			return $category['category_id'];
		}, $catlink_categories);

		$available_categories = array_filter($categories, function($category) use ($assigned_category_ids) {
			return !in_array($category['id'], $assigned_category_ids);
		});

		$product = (new shopProductModel())->getById($product_id);

		$this->view->assign(array(
			'categories' => $available_categories,
			'product_id' => $product_id,
			'catlink_categories' => $catlink_category_ids,
			'product' => $product,
		));
	}


}
