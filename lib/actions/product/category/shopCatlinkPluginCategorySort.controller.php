<?php


class shopCatlinkPluginCategorySortController extends waJsonController
{
	public function execute()
	{
		$categories = waRequest::post('categories', '', 'string');
		if(!mb_strlen($categories)) {$this->response = array('result' => 0, 'message' => 'Системная ошибка #NOARR'); return;}
        $categories = str_replace("categories= ", "", $categories);
        $arrCategories = explode(',', $categories);
            
		$catlink = waSystem::getInstance('shop')->getPlugin('catlink');
		$this->response = $catlink->sortCategory($arrCategories);
	}
}