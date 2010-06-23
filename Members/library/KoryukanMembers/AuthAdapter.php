<?php
/**
 * KoryukanMembers_AuthAdapter Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-06-20
 *
 */

/**
 * Authentication adapter
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class KoryukanMembers_AuthAdapter implements Zend_Auth_Adapter_Interface
{
    /**
     * The username
     *
     * @var string
     */
    protected $_username;

    /**
     * The password
     *
     * @var string
     */
    protected $_password;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct($username, $password)
    {
        $this->_username = $username;
        $this->_password = $password;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot
     *                                     be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        $user = Koryukan_Model_User::getByUsername($this->_username);

        if (isset($user)) {
            if ($user->validatePassword($this->_password)) {
                return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user);
            }
        }

        return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null);
    }
}