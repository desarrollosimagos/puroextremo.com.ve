<?php
class RatingsProductlist extends Module {
	public function __construct() {
		$this -> name = 'ratingsproductlist';
		$this -> version = '1.0';
		$this -> dependencies = array('productcomments');
		$this->author = 'IQIT-COMMERCE.COM';
		parent::__construct();

		$this -> displayName = $this->l('Products ratings on product page');
		$this -> description = $this->l('Add Stars under product title');
	}

	public function install() {
		return parent::install() && $this -> registerHook('productratingHook');
	}

	public function hookproductratingHook($params) {

		$product = $params['productid'];

		include_once (_PS_MODULE_DIR_ . 'productcomments/ProductComment.php');
		if (!isset($product)) {
			return;
		}
		$grade = ProductComment::getAverageGrade($product);
		$this -> context -> smarty -> assign(array('empty_grade' => 1));
		if (isset($grade['grade']))
			$this -> context -> smarty -> assign(array('empty_grade' => 0));
		$this -> context -> smarty -> assign(array(
			'average_total' => round($grade['grade']),
			'nbComments' => (int)(ProductComment::getCommentNumber((int)$product))
			));


		$this -> context -> smarty -> assign((int)(ProductComment::getCommentNumber((int)$product)));

		return ($this -> display(__FILE__, 'ratingsproductlist.tpl'));

	}

}
