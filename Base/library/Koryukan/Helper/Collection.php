<?php
/**
 * Koryukan_Helper_Collection Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-05-28
 *
 */

/**
 * Collection class
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class Koryukan_Helper_Collection implements Countable, Iterator
{
    /**
     * The query
     *
     * @var Doctrine_Query
     */
    protected $_query;

    /**
     * The data
     *
     * @var Doctrine_Collection
     */
    protected $_data;

    /**
     * The data iterator
     *
     * @var Iterator
     */
    protected $_iterator;


    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Doctrine_Query $query)
    {
        $this->_query = $query;
        $cacheKey = sha1($query->getSqlQuery() . serialize($query->getParams()));

        $cache = Zend_Registry::get('mainCache');

        if ($cache->test($cacheKey)) {
            $data = $cache->load($cacheKey);
        } else {
            $data = $query->execute();
            $cache->save($data, $cacheKey);
        }

        $this->_data = $data;
        $this->_iterator = $data->getIterator();
    }

    /**
     * Return how many items are in the collection
     *
     * @return void
     */
    public function count()
    {
        return $this->_data->count();
    }


    function rewind() {
        $this->_iterator->rewind();
    }

    function current() {
        return $this->_iterator->current();
    }

    function key() {
        return $this->_iterator->key();
    }

    function next() {
        $this->_iterator->next();
    }

    function valid() {
        return $this->_iterator->valid();
    }

}