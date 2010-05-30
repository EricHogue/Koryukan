<?php
/**
 * Koryukan_View_Helper_Purify Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-05-30
 *
 */

/**
 * Purifiy output with HTMLPurifier
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class Koryukan_View_Helper_Purify extends Zend_View_Helper_Abstract
{
    /**
     * The HTML purifier object
     *
     * @var HTMLPurifier
     */
    protected $_purifier;

    /**
     * Contructor
     *
     * @return void
     */
    public function __construct(HTMLPurifier $purifier)
    {
        $this->_purifier = $purifier;
    }

    /**
     * Purify
     *
     * @return void
     */
    public function purify($dirtyHtml)
    {
        return $this->_purifier->purify($dirtyHtml);
    }
}