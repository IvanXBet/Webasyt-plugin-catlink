<?php
return array
	(
		'name' => 'catlink',
		'version' => '1.0.0',
		'vendor' => 995002,
		'description' => 'Позволяет добавить для товара связанную категорию',
		'img' => 'img/category.svg',
		'handlers' => array
						(
							'backend_product' => 'backendProduct',
							'frontend_product' => 'frontendProduct',
						),
	);