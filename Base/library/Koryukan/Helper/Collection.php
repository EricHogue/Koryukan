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
    const SERIALIZATION_DEEP = 10;

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
    protected $_dbData;

    /**
     * The data iterator
     *
     * @var Iterator
     */
    protected $_iterator;

    /**
     * The cache key
     *
     * @var string
     */
    protected $_cacheKey;



    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Doctrine_Query $query)
    {
        $this->_query = $query;
        $this->_cacheKey = sha1($query->getSqlQuery() . serialize($query->getParams()));

        $cache = Zend_Registry::get('mainCache');

        if ($cache->test($this->_cacheKey)) {
            $this->_loadFromCache($cache);
            echo('From cache<br />');
        } else {
            $this->_dbData = $this->_query->execute();
            $this->_saveInCache($cache);
            echo('From DB<br />');
        }

        $this->_iterator = $this->_dbData->getIterator();
    }

    /**
     * Save in cache
     *
     * @return void
     */
    private function _saveInCache(Zend_Cache_Core $cache)
    {
        $cacheData = array();

        $cacheData['tableName'] = $this->_dbData->getTable()->getComponentName();
        $cacheData['dataArray'] = $this->_dbData->toArray(self::SERIALIZATION_DEEP);

        $cache->save($cacheData, $this->_cacheKey);
    }

    /**
     * Load the data from cache
     *
     * @return void
     */
    private function _loadFromCache(Zend_Cache_Core $cache)
    {
        $cacheData = $cache->load($this->_cacheKey);
        $dbData = new Doctrine_Collection($cacheData['tableName']);
        $dbData->fromArray($cacheData['dataArray'], self::SERIALIZATION_DEEP);

        $this->_dbData = $dbData;
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