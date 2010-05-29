<?php

/**
 * Koryukan_Db_NewsTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Koryukan_Db_NewsTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object Koryukan_Db_NewsTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Koryukan_Db_News');
    }

    /**
     * Load the news
     *
     * @return void
     */
    public function getNews($status)
    {
        $query = $this->createQuery('news');
        $query->innerJoin('news.NewsText');

        if (strlen($status) > 0) {
            $query->addWhere('Status = ?', $status);
        }

        return $query->execute();
    }

}