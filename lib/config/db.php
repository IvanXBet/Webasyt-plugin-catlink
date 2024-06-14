<?php
return array(
    'shop_catlink_product_category' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'product_id' => array('int', 11, 'null' => 0),
        'category_id' => array('int', 11, 'null' => 0),
        'name' => array('varchar', 255, 'null' => 0),
        'sort' => array('int', 11, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
);
