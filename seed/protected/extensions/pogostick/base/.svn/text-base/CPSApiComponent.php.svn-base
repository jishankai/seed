<?php
/**
 * CPSApiComponent class file.
 *
 * @author Jerry Ablan <jablan@pogostick.com>
 * @link http://ps-yii-extensions.googlecode.com
 * @copyright Copyright &copy; 2009 Pogostick, LLC
 * @license http://www.pogostick.com/license/
 */
/**
 * CPSApiComponent provides a convenient base class for APIs
 *
 * @author Jerry Ablan <jablan@pogostick.com>
 * @version SVN: $Id: CPSApiComponent.php 233 2009-06-10 23:54:16Z jerryablan@gmail.com $
 * @filesource
 * @package psYiiExtensions
 * @subpackage Base
 * @since 1.0.0
 */
class CPSApiComponent extends CPSComponent
{
	//********************************************************************************
	//* Public Methods
	//********************************************************************************

	/**
	* Constructor
	*
	*/
	public function __construct()
	{
		//	Call daddy...
		parent::__construct( $this );

		//	Attach our default behavior
		$this->attachBehavior( $this->m_sInternalName, 'pogostick.behaviors.CPSApiBehavior' );

		//	Log it and check for issues...
		CPSCommonBase::writeLog( Yii::t( $this->m_sInternalName, '{class} constructed', array( "{class}" => get_class( $this ) ) ), 'trace', $this->m_sInternalName );
	}

	//********************************************************************************
	//* Private Methods
	//********************************************************************************

	//********************************************************************************
	//* Events and Handlers
	//********************************************************************************

	/**
	 * Declares events and the corresponding event handler methods.
	 * @return array events (array keys) and the corresponding event handler methods (array values).
	 * @see CBehavior::events
	 */
	public function events()
	{
		return(
			array_merge(
				parent::events(),
				array(
					'onBeforeApiCall' => 'beforeApiCall',
					'onAfterApiCall' => 'afterApiCall',
					'onRequestComplete' => 'requestComplete',
				)
			)
		);
	}

	/**
	* Call to raise the onBeforeApiCall event
	*
	* @param CPSApiEvent $oEvent
	*/
	public function beforeApiCall( $oEvent )
	{
		$this->onBeforeApiCall( $oEvent );
	}

	/**
	* Raises the onBeforeApiCall event
	*
	* @param CPSApiEvent $oEvent
	*/
	public function onBeforeApiCall( $oEvent )
	{
		$this->raiseEvent( 'onBeforeApiCall', $oEvent );
	}

	/**
	* Call to raise the onAfterApiCall event
	*
	* @param CPSApiEvent $oEvent
	*/
	public function afterApiCall( $oEvent )
	{
		$this->onAfterApiCall( $oEvent );
	}

	/**
	* Raises the onAfterApiCall event. $oEvent contains "raw" return data
	*
	* @param CPSApiEvent $oEvent
	*/
	public function onAfterApiCall( $oEvent )
	{
		$this->raiseEvent( 'onAfterApiCall', $oEvent );
	}

	/**
	* Call to raise the onRequestComplete event
	*
	* @param CPSApiEvent $oEvent
	*/
	public function requestComplete( $oEvent )
	{
		$this->onRequestComplete( $oEvent );
	}

	/**
	* Raises the onRequestComplete event. $oEvent contains "processed" return data (if applicable)
	*
	* @param CPSApiEvent $oEvent
	*/
	public function onRequestComplete( $oEvent )
	{
		$this->raiseEvent( 'onRequestComplete', $oEvent );
	}

}