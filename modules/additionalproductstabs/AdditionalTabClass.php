<?php


class AdditionalTabClass extends ObjectModel
{

	public $id;

	public $id_shop;

	public $id_product;

	public $activeAdditionalTab;

	public $titleAdditionalTab;

	public $contentAdditionalTab;


	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'additionalproductstabs',
		'primary' => 'id_additionalproductstab',
		'multilang' => true,
		'fields' => array(
			'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'activeAdditionalTab' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'titleAdditionalTab' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
			'contentAdditionalTab' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
		)
	);

	public static function getTab($id_shop, $id_product)
	{
		$id = Db::getInstance()->getValue('SELECT `id_additionalproductstab` FROM `'._DB_PREFIX_.'additionalproductstabs` WHERE `id_product` = '.(int)$id_product.' AND `id_shop` ='.(int)$id_shop);

		return new AdditionalTabClass($id);
	}

	



	public function copyFromPost()
	{
		/* Classical fields */
		foreach ($_POST as $key => $value)
		{
			if (key_exists($key, $this) && $key != 'id_'.$this->table)
				$this->{$key} = $value;
		}

		/* Multilingual fields */
		if (count($this->fieldsValidateLang))
		{
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				foreach ($this->fieldsValidateLang as $field => $validation)
				{
					if (Tools::getIsset($field.'_'.(int)$language['id_lang']))
						$this->{$field}[(int)$language['id_lang']] = $_POST[$field.'_'.(int)$language['id_lang']];
				}
			}
		}
	}
}