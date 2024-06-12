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
			$category['url'] = wa()->getRouteUrl(
				'shop/frontend/category',
				array('category_url' => $category['full_url'])
			);
		}
		
		unset($category);
		// Подготовить данные для шаблона
		
		$view->assign('ctl_categories', $categories);
		return $view->fetch(wa()->getAppPath(null, 'shop') . '/plugins/catlink/templates/FrontendCatlinkBlock.html');
	}
	
	/////////////////////////////////////////////////////////////////////////////////////
	// Работа с категориями 
	/////////////////////////////////////////////////////////////////////////////////////
	public function getFormattedCategories() 
	{
		$category_model = new shopCategoryModel();
		$categories = $category_model->getAll();

		$formatted_categories = array();
		foreach ($categories as $category) {
			$category_path = self::getCategoryPathById( $categories, $category['id']);
			$formatted_categories[] = array(
				'id' => $category['id'],
				'name' => $category['name'],
				'path' => $category_path
			);
		}

		return array('result' => 1, 'Categories' => $formatted_categories);
	}

	function getCategoryPathById($categories, $category_id) {
		
		$category = null;
		foreach ($categories as $cat) {
			if ($cat['id'] == $category_id) {
				$category = $cat;
				break;
			}
		}

		if (!$category) {
			return null; 
		}
		
		$path = $category['name'];
		$parent_id = $category['parent_id'];
		while ($parent_id != 0) {
			$parent_category = null;
			foreach ($categories as $cat) {
				if ($cat['id'] == $parent_id) {
					$parent_category = $cat;
					break;
				}
			}
			if (!$parent_category) {
				break; 
			}
			$path = $parent_category['name'] . ' > ' . $path;
			$parent_id = $parent_category['parent_id'];
		}

		return $path;
	}

	public function addCategory($data) 
	{
		$catlink_model = new shopCatlinkPluginProductCategoryModel();
		
		if (!isset($data['product_id']) || empty($data['product_id'])) {
			$result[] = array('result' => 0, 'message' => 'Продукт не найден', 'data' => $data);
		}
		else 
		{
			$product_id = $catlink_model->escape($data['product_id']);
			$data = array(
				'product_id' => $product_id,
				'category_id' => $catlink_model->escape($data['category_id']),
				'name' => $catlink_model->escape($data['name']),
				'sort' => $catlink_model->getMaxSort($product_id)+1,
			);
			if($catlink_model->getByField($data))
			{
				$result[] = array('result' => 0, 'message' => 'Эта категория уже добавлена');
			}
			else{
				$catlink_model->insert($data);
				$result[] = array('result' => 1, 'message' => 'Категория добавлена к продукту');
			}
		}
		return $result;
	}

	public function sortCategory($cat) 
	{
		if(!count($cat)) {return array('result' => 0, 'message' => 'Не заданн список для сортировки');}
		$catlink_model = new shopCatlinkPluginProductCategoryModel();
		return $catlink_model->sortSets($cat);
	}
	

	public function deleteCategory($data) 
	{
		
		if (!isset($data['product_id']) || empty($data['product_id'])) {
			$result[] = array('result' => 0, 'message' => 'Продукт не найден', 'data' => $data);
		}
		else
		{
			$catlink_model = new shopCatlinkPluginProductCategoryModel();
			$data = array(
				'product_id' => $catlink_model->escape($data['product_id']),
				'category_id' => $catlink_model->escape($data['category_id']),
			);
			
			$res = $catlink_model->deleteByField($data);
			if(!$res)
			{
				$result[]= array('result' => 1, 'message' => 'Ошибка удаления');
			}
			$result[]= array('result' => 1, 'message' => 'Успешное удаление', 'asdf' => $data);
		}
		
		return $result;
	}
}
