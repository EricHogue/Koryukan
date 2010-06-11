<?php

/**
 * Koryukan_Db_ImageSectionTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Koryukan_Db_ImageSectionTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object Koryukan_Db_ImageSectionTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Koryukan_Db_ImageSection');
    }

    /**
     * Return all the section with the images fully loaded
     *
     * @return void
     */
    public function getAllSectionWithImages()
    {
        $query = $this->createQuery('section');
        $query->innerJoin('section.ImageSectionText');
        $query->innerJoin('section.ImageFile file');
        $query->innerJoin('file.ImageText');
        $query->orderBy('section.sectiondate Desc, file.useonsection Desc');

        return $query;
    }
}