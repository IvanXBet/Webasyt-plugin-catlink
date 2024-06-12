<?php
class shopCatlinkPluginCategoryListController extends waJsonController
{
	public function execute()
    {
        $product_id = waRequest::post('productId', null);
        $catlink_model = new shopCatlinkPluginProductCategoryModel();
		// $categories = $catlink_model->getByField('product_id', $product_id, true, 'sort ASC');
        $categories = $catlink_model->query("SELECT * FROM shop_catlink_product_category WHERE product_id = ".$product_id." ORDER BY sort ASC")->fetchAll();
        $this->response = $categories;
    }
}


