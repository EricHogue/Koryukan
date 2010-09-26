<?php
/**
 * AdministrationController Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-09-06
 *
 */

/**
 * Administration controller
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Members
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class AdministrationController extends Zend_Controller_Action
{
    /**
     * User manager
     *
     * @return void
     */
    public function usersAction()
    {

    }

    /**
     * Return the list of users
     *
     * @return void
     */
    public function getusersAction()
    {
        $page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx =1;
// connect to the database

$count = 1;
if( $count >0 ) {
    $total_pages = ceil($count/$limit);
} else {
    $total_pages = 0;
}

if ($page > $total_pages) $page=$total_pages;


$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;



    $responce->rows[0]['id']=1;
    $responce->rows[0]['cell']=array(1);

/*
echo json_encode($responce);
$this->getHelper('viewRenderer')->setNoRender();
$this->_helper->layout->disableLayout();
*/

$jsonData = Zend_Json::encode($responce);
    $this->getResponse()
    ->setHeader('Content-Type', 'text/json')
    ->setBody($jsonData);
    //->sendResponse();

    $this->getHelper('viewRenderer')->setNoRender();
$this->_helper->layout->disableLayout();

//echo $jsonData;

    }

}