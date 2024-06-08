<?php

class shopCatlinkPlugin  extends shopPlugin

{
	
	/////////////////////////////////////////////////////////////////////////////////////
	// Хуки
	/////////////////////////////////////////////////////////////////////////////////////
	
	public function backendProduct($data)
	{
		$view = wa()->getView();

		$plugin = waRequest::get('plugin', '', 'string');
		$module = waRequest::get('module', '', 'string');
		$action = waRequest::get('action', '', 'string');
		
		$catlink_core_li_class = 'no-tab';
		if($plugin == 'catlink' && $module == 'backend' && $action == 'control') {$catlink_core_li_class = 'selected';}
		$view->assign('catlink_core_li_class', $catlink_core_li_class);

		$view->assign('product_id', $data['id']);
		return array('edit_section_li' => $view->fetch(wa()->getAppPath(null, 'shop').'/plugins/catlink/templates/BackendMenuEditSectionLi.html'));

	}

	static public function frontendProductCatlink($product_id) 
	{
		$catlink_model = new shopCatlinkPluginProductCategoryModel();
		$category_model = new shopCategoryModel();

		$add_category = $catlink_model->getByField('product_id', $product_id, true);
				if (!is_array($add_category)) {
			$add_category = [];
		}
		print_r($add_category);

		$category_ids = array();

		foreach ($add_category as $item) {
			// Проверяем, существует ли ключ 'category_id' в текущей записи
			if (isset($item['category_id'])) {
				// Добавляем значение 'category_id' в массив $category_ids
				$category_ids[] = $item['category_id'];
			}
		}

		$view = wa()->getView();
		// Получить детали категорий по ID
		$categories = $category_model->getById($category_ids);
		foreach ($categories as &$category) {
			$category['url'] = wa()->getRouting()->getUrl('frontend/category', array('category_id' => $category['id']));
		}
		unset($category);
		// Подготовить данные для шаблона
		
		$view->assign('ctl_categories', $categories);
		return $view->fetch(wa()->getAppPath(null, 'shop') . '/plugins/catlink/templates/FrontendCatlinkBlock.html');
	}
	
	/////////////////////////////////////////////////////////////////////////////////////
	// Работа с категориями 
	/////////////////////////////////////////////////////////////////////////////////////


	public function updateCategory($data) 
	{
		$catlink_model = new shopCatlinkPluginProductCategoryModel();
		$isChecked = $data['is_checked'];
		if (!isset($data['product_id']) || empty($data['product_id'])) {
			return array('result' => 0, 'message' => 'Продукт не найден', 'data' => $data);
		}
		if(!($isChecked === 'true' || $isChecked === 'false')) {
			return array('result' => 0, 'message' => 'Ошибка сохранения', 'data' => $data);
		}
		
		$data = array(
				'product_id' => $catlink_model->escape($data['product_id']),
				'category_id' => $catlink_model->escape($data['category_id']),
			);

		if($isChecked === 'true')
		{
			if($catlink_model->getByField($data))
			{
				return $result[] = array('result' => 0, 'message' => 'Этоа категория уже добавлена');
			}
			$catlink_model->insert($data);
			$result[] = array('result' => 1, 'message' => 'Категория добавлена к продукту');
		}
		
		if($isChecked === 'false') 
		{
			$catlink_model->deleteByField($data);
			$result[] = array('result' => 1, 'message' => 'Категория откреплена от продукта');
		}
		return $result;
	}
}
