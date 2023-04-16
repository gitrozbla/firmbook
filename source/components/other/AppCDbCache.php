<?php


class AppCDbCache extends CDbCache
{
	
	public function delete_item($key)
	{
		return;
		echo '<br>delete_item';
// 		$key = $this->generateUniqueKey($key);
		echo '<br>key:'.$this->generateUniqueKey($key);
		echo '<br>key:'.md5($this->generateUniqueKey($key));
// 		echo '<br>key:'.$key;
// 		$key = $this->getValue($key);
		echo '<br>key:'.$key;
// 		$this->delete($key);
		$this->deleteValue($key);
	}
	
}