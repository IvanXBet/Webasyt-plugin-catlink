<?php


class shopCatlinkPluginProductControlCatlinkAction extends waViewAction
{
	public function execute()
	{
		$product_id = waRequest::get('id', 0, 'int');
		$this->view->assign('product_id', $product_id);
	}
}
