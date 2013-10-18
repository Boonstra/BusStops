<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stefan
 * Date: 18-10-13
 * Time: 13:29
 * To change this template use File | Settings | File Templates.
 */
class ActiveRecord extends CActiveRecord
{
	public function populateRecord($attributes, $callAfterFind = true)
	{
		if ($this->useTypeCasting() and is_array($attributes))
		{
			foreach ($attributes as $name => &$value)
			{
				if ($this->hasAttribute($name) and $value !== null)
				{
					settype($value, $this->getMetaData()->columns[$name]->type);
				}
			}
		}

		return parent::populateRecord($attributes, $callAfterFind);
	}

	public function useTypeCasting()
	{
		return false;
	}
}