<?php 
    class shopCatlinkPluginProductCategoryModel extends waModel
    {
        protected $table = "shop_catlink_product_category";

        public function getMaxSort($product_id) 
        {
            $data = $this->query("SELECT  MAX(sort) AS mx FROM ".$this->table." WHERE product_id = ".$product_id)->fetchAll();
           
            if(!count($data)) {return 0;}
            return $data[0]["mx"];
        }

        public function sortSets($arrSest)
        {

            if(!count($arrSest)) {return;}
            $sort = 1;
            foreach($arrSest as $key => $id) {
                $this->updateById($id, array('sort' => $sort));
                $sort++;
            }
            return array(
                'data' => $arrSest,
                'mas' => 'Готово',
            );
        }

    }