<?php
/**
 * RecordModel类绑定的接口方法
 * 
 * @author user
 *
 */
Interface RecordInterface {
	/**
	 * 静态方法 返回一个允许使用的属性的列表数组
	 * 
	 * @return array 
	 */
	static function attributeColumns();
	
	/**
	 * 静态方法 返回一堆对象的数组
	 * 
	 * @param array $params 条件参数 根据具体情况自行定义
	 * @param boolean $isSimple 是否是简易数据类型
	 */
	static function multiLoad( $params=array(),$isSimple=true );
} 

