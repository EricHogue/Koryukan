<?php

/**
 * Validate the info in a user
 *
 */
class KoryukanMembers_UserValidator extends Zend_Validate_Abstract
{
	/**
	 * The user to validate
	 *
	 * @var Koryukan_Model_User
	 */
	protected $_user;

	/**
	 * The view
	 *
	 * @var Zend_View_Interface
	 */
	protected $_view;

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct(Zend_View_Interface $view)
	{
	    $this->_view = $view;
	}



	/**
	 * Make sure the user is valid
	 *
	 * @return void
	 */
	public function isValid($value)
	{
		$valid = true;
		$this->_user = $value;

		$namePartsValidator = new Zend_Validate();
		$namePartsValidator->addValidator(new Zend_Validate_NotEmpty(Zend_Validate_NotEmpty::STRING))
							->addValidator(new Zend_Validate_Alpha(array('allowWhiteSpace' => true)))
							->addValidator(new Zend_Validate_StringLength(array('min' => 2)));

		if (!$namePartsValidator->isValid($this->_user->getFirstName())) {
			$valid = false;
			$this->_error($this->_view->translate('The first name must have at least 2 characters and consist only of letters'));
		}

		if (!$namePartsValidator->isValid($this->_user->getLastName())) {
			$valid = false;
			$this->_error($this->_view->translate('The last name must have at least 2 characters and consist only of letters'));
		}

		$emailValidator = new Zend_Validate_EmailAddress();
		if (!$emailValidator->isValid($this->_user->getEmail())) {
			$valid = false;
			$this->_error($this->_view->translate('You must entre a valid email'));
		}

		if ($this->_user->isNew()) {
			$usernameValidator = new Zend_Validate();
			$usernameValidator->addValidator(new Zend_Validate_NotEmpty(Zend_Validate_NotEmpty::STRING))
							->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => false)))
							->addValidator(new Zend_Validate_StringLength(array('min' => 5)));
			if (!$usernameValidator->isValid($this->_user->getUsername())) {
				$this->_error($this->_view->translate('The username must have at least 5 characters and contains no white spaces'));
			}
		}

		return $valid;
	}
}