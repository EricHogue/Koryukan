<?php
/**
 * ContactsController Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Public
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-06-05
 *
 */

/**
 * Contacts info
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Public
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class ContactsController extends Zend_Controller_Action
{
    /**
     * Index
     *
     * @return void
     */
    public function indexAction()
    {
        $lang = $this->getRequest()->getParam('lang', 'en');
        if (!in_array($lang, array('en', 'fr'))) {
            $lang = 'en';
        }

        $pageTitle = $this->view->translate('Dojo & Contact Info');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImage', 'contacts.jpg');
        $this->view->assign('sideImageAlt', $pageTitle);
        $this->view->assign('mapLanguage', $lang);
    }
}