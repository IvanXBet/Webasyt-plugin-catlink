<?php
class shopCatlinkPluginCategoryGetController extends waJsonController
{
	 public function execute()
    {
        $catlink = waSystem::getInstance('shop')->getPlugin('catlink');
		$this->response = $catlink->getFormattedCategories();
    }
}


