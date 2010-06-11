<?php
/**
 * MultimediaController Class File
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
 * MultimediaController
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Public
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class MultimediaController extends Zend_Controller_Action
{
    /**
     * Index
     *
     * @return void
     */
    public function indexAction()
    {
        $pageTitle = $this->view->translate('Multimedia');
        $this->view->assign('pageTitle', $pageTitle);
        $this->view->assign('sideImage', 'Multimedia.jpg');
        $this->view->assign('sideImageAlt', $pageTitle);

        $sections = Koryukan_Model_ImageSection::getAllSectionWithImages();
        $this->view->assign('imageSections', $sections);
        $this->view->assign('lang', $this->getRequest()->getParam('lang', 'en'));
    }
}