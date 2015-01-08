<?php

class Model extends CComponent
{
	/**
	 * 获取/设置缓存数据
	 *  
	 * @param string $key 缓存索引
	 * @param mixed  $vlaue 要设置的值 取数据请留空此值
	 * 
	 * @return true / cache data
	 */
	public function cache($key,$value=KEY_NOT_SET_VALUE){
		$key = $this->getCacheKey($key);
		if( $value==KEY_NOT_SET_VALUE ){
			return Yii::app()->cache->get($key) ;
		}
		else {
			Yii::app()->cache->set($key, $value);
			return true ;
		}
	}
	
	/**
	 * 清除所有缓存 
	 * 
	 *  @return void 
	 */
	public function flushCache(){
		Yii::app()->cache->flush();
	}

	/**
	 * 处理缓存的key值 自动拼合当前的类名 避免不同类key重复问题
	 * @param string $key
	 */
	protected function getCacheKey($key){
		return get_class($this) . '_' . $key;
	}
	
}

?>
