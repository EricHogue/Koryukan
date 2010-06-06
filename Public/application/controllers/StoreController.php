<?php
/**
 * StoreController Class File
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
 * StoreController
 *
 * @category   PHP5
 * @package    package
 * @subpackage subPackage
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class StoreController extends Zend_Controller_Action
{
    /**
     * Index
     *
     * @return void
     */
    public function indexAction()
    {
        $pageTitle = $this->view->translate('Online store');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImage', 'store' . $this->getRequest()->getParam('lang', 'en') . '.png');
        $this->view->assign('sideImageAlt', $pageTitle);

        $items = Koryukan_Model_StoreItem::getItems();
        $this->view->assign('items', $items);
        $this->view->assign('lang', $this->getRequest()->getParam('lang', 'en'));
    }
}