<?php
/**
 * 
 * Enter description here ...
 * @author user
 *
 */
class PropModel {
	
	private $modelList = array(
		PROP_TYPE_SEED		=> 'Seed' ,
		PROP_TYPE_EQUIPMENT	=> 'Equipment' ,	
	);
	

	/**
	 * 获得 一个新的道具
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function getProp( $type,$id ) {
		
	}
	

	/**
	 * 获得/提取 一个已经存在的道具
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function fetchProp( $type,$id ) {
		
	}
	
	
	/**
	 * 扔掉/移除一个道具
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function removeProp( $type,$id ) {
		
	}
	
	/**
	 * 卖出道具
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function sellProp( $type,$id ) {
		$prop = $this->getProp($type, $id);
		$price = $prop->getPropPrice();
	}
	
	/**
	 * 购买道具
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function buyProp( $type,$id ) {
	    
	}
	
	
	
	private function getPropObject( $type,$id ){
		if( !isset($this->modelList[$type]) ) {
			throw new CException('Prop type not defined.');
		}
		return Yii::app()->objectLoader->load( $this->modelList[$type],$id );
	}
	
} 

