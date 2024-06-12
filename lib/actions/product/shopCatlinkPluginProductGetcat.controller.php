<?php
class shopCatlinkPluginProductGetcatController extends waJsonController
{
	 public function execute()
    {
        $category_model = new shopCategoryModel();
        $categories = $category_model->getAll();

        // Функция для получения полного пути категории
        function getCategoryPath($category_id, $categories) {
            $category_path = '';
            while ($category_id != 0) {
                $category = $categories[$category_id];
                $category_path = $category['name'] . ($category_path ? ' > ' : '') . $category_path;
                $category_id = $category['parent_id'];
            }
            return $category_path;
        }

        $formatted_categories = array();
        foreach ($categories as $category) {
            $category_path = getCategoryPath($category['id'], $categories);
            $formatted_categories[] = array(
                'id' => $category['id'],
                'name' => $category['name'],
                'path' => $category_path
            );
        }

        $this->response = array('categories' => $formatted_categories);
    }
}