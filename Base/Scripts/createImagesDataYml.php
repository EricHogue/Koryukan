<?php
/**
 * CreateImagesDataYml Class File
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 * @copyright  2010 Gamma Entertainment. All Rights Reserved
 * @license    http://www.gammae.com/license
 * @version    SVN: $Id$
 * @link       SVN: $HeadURL$
 * @since      2010-06-06
 *
 */

$creator = new CreateImagesDataYml();
$creator->createData();
unset($creator);

/**
 * classDescription
 *
 * @category   PHP5
 * @package    Koryukan
 * @subpackage Base
 * @author     Eric Hogue <eric@erichogue.ca>
 */
class CreateImagesDataYml
{
    const TAB = '    ';

    /**
     * The merge of the french and english arrays
     *
     * @var array
     */
    protected $_mergedArrays;

    /**
     * Image files
     *
     * @var array
     */
    protected $_imageFiles;

    /**
     * sectionTextId
     *
     * @var int
     */
    protected $_sectionTextId;

    /**
     * The id to use for the image text
     *
     * @var int
     */
    protected $_imageTextId;



    /**
     * Create the data
     *
     * @return void
     */
    public function createData()
    {
        $this->_mergeArrays();
        $this->_buildImageFilesArray();
        $this->_addIds();

        $yaml = $this->_convertToYaml();
        $this->_writeYamlToFile('/media/Data/Workspace/Koryukan/Base/Scripts/Yaml/Data/images.yml', $yaml);
    }

    /**
     * Merge the arrays
     *
     * @return void
     */
    private function _mergeArrays()
    {
        $merged = $this->_addFrenchSections();
        $merged = $this->_addEnglishInfoToSections($merged);

        $this->_mergedArrays = $merged;
    }

    /**
     * Add the french sections
     *
     * @return array
     */
    private function _addFrenchSections()
    {
        $merged = array();
        foreach ($this->fr as $section) {
            $modifiedSection = array();
            $modifiedSection['SectionMainImage'] = $section['SectionMainImage'];
            $modifiedSection['SectionSmallImage'] = $section['SectionSmallImage'];
            $modifiedSection['SectionDate'] = $section['SectionDate'];
            $modifiedSection['SectionTitleFr'] = $section['SectionTitle'];
            $modifiedSection['Images'] = $section['Images'];

            $merged[$section['SectionMainImage']] = $modifiedSection;
        }

        return $merged;
    }

    /**
     * Add the english sections info
     *
     * @return array
     */
    private function _addEnglishInfoToSections(array $merged)
    {
        foreach ($this->en as $englishSection) {
            if (array_key_exists($englishSection['SectionMainImage'], $merged)) {
                $mergedSection = $merged[$englishSection['SectionMainImage']];
                $mergedSection['SectionTitleEn'] = $englishSection['SectionTitle'];

                $mergedSection['Images'] = $this->_mergeSectionImages($mergedSection['Images'], $englishSection['Images']);

                $merged[$englishSection['SectionMainImage']] = $mergedSection;
            } else {
                error_log($englishSection['SectionMainImage'] . ' is not in the merged array');
            }

        }

        return $merged;
    }

    /**
     * Merge the images of a section
     *
     * @return array
     */
    private function _mergeSectionImages(array $imagesFr, array $imagesEn)
    {
        $mergedImages = array();

        foreach ($imagesFr as $frenchImage) {
            $mergedImages[$frenchImage['Image']] = array('Image' => $frenchImage['Image'],
                'DescriptionFr' => $frenchImage['Description']);
        }

        foreach ($imagesEn as $englishImage) {
            if (array_key_exists($englishImage['Image'], $mergedImages)) {
                $mergedImages[$englishImage['Image']]['DescriptionEn'] = $englishImage['Description'];
            } else {
                error_log('The image ' . $englishImage['Image'] . ' does not exists in french');
            }
        }

        return $mergedImages;
    }

    /**
     * Build the image files array
     *
     * @return void
     */
    private function _buildImageFilesArray()
    {
        $imagesFiles = array();
        $imageCounter = 1;

        foreach ($this->_mergedArrays as $section) {
            if (!array_key_exists($section['SectionMainImage'], $imagesFiles)) {
                $imagesFiles[$section['SectionMainImage']] = array('counter' => $imageCounter, 'type' => 'normal',
                    'useOnSection' => true);
                $imageCounter++;
            }

            if (!array_key_exists($section['SectionSmallImage'], $imagesFiles)) {
                $imagesFiles[$section['SectionSmallImage']] = array('counter' => $imageCounter, 'type' => 'thumb',
                    'useOnSection' => true);
                $imageCounter++;
            }

            foreach ($section['Images'] as $image) {
                if (!array_key_exists($image['Image'], $imagesFiles)) {
                    $imagesFiles[$image['Image']] = array('counter' => $imageCounter, 'type' => 'normal',
                        'useOnSection' => false);
                    $imageCounter++;
                }
            }
        }

        $this->_imageFiles = $imagesFiles;
    }

    /**
     * Add id to sections
     *
     * @return void
     */
    private function _addIds()
    {
        $sectionCount = 1;
        foreach ($this->_mergedArrays as $section) {
            $sectionId = 'section' . $sectionCount;
            $section['sectionId'] = $sectionId;
            $sectionCount++;

            $this->_mergedArrays[$section['SectionMainImage']] = $section;
        }
    }

    /**
     * Convert to yaml
     *
     * @return void
     */
    private function _convertToYaml()
    {
        $yaml = "Koryukan_Db_ImageSection:\n";
        foreach ($this->_mergedArrays as $section) {
            $yaml .= $this->_convertSectionToYaml($section);
        }

        $this->_sectionTextId = 1;
        $yaml .= "Koryukan_Db_ImageSectionText:\n";
        foreach ($this->_mergedArrays as $section) {
            $yaml .= $this->_convertSectionTextToYaml($section);
        }

        $yaml .= "Koryukan_Db_ImageFile:\n";
        foreach ($this->_mergedArrays as $section) {
            $yaml .= $this->_convertSectionImagesToYaml($section);
        }

        $this->_imageTextId = 1;
        $yaml .= "Koryukan_Db_ImageText:\n";
        foreach ($this->_mergedArrays as $section) {
            $yaml .= $this->_convertSectionImageTextToYaml($section);
        }

        return $yaml;
    }

    /**
     * convert the sectios to yaml
     *
     * @return void
     */
    private function _convertSectionToYaml(array $section)
    {
        $yaml = self::TAB . $section['sectionId'] . ":\n";
        $yaml .= self::TAB . self::TAB . "sectionDate: '" . $section['SectionDate'] . "'\n";

        return $yaml;
    }

    /**
     * Convert the section text to yaml
     *
     * @return void
     */
    private function _convertSectionTextToYaml(array $section)
    {
        $yaml = self::TAB . 'sectionText' . $this->_sectionTextId . ":\n";
        $yaml .= self::TAB . self::TAB . "ImageSection: " . $section['sectionId'] . "\n";
        $yaml .= self::TAB . self::TAB . "language: fr\n";
        $yaml .= self::TAB . self::TAB . "title: " . str_replace("\n", ' ', $section['SectionTitleFr']) . "\n";
        $this->_sectionTextId++;

        $yaml .= self::TAB . 'sectionText' . $this->_sectionTextId . ":\n";
        $yaml .= self::TAB . self::TAB . "ImageSection: " . $section['sectionId'] . "\n";
        $yaml .= self::TAB . self::TAB . "language: en\n";
        $yaml .= self::TAB . self::TAB . "title: " . str_replace("\n", ' ', $section['SectionTitleEn']) . "\n";
        $this->_sectionTextId++;

        return $yaml;
    }

    /**
     * Write Yaml to a file
     *
     * @return void
     */
    private function _writeYamlToFile($filename, $yaml)
    {
        file_put_contents($filename, $yaml);
    }

    /**
     * Convert the images of a section to yaml
     *
     * @return void
     */
    private function _convertSectionImagesToYaml(array $section)
    {
        $yaml = '';
        $sectionId = $section['sectionId'];

        foreach ($section['Images'] as $image) {
            $imageInfo = $this->_imageFiles[$image['Image']];

            $yaml .= self::TAB . 'image' . $imageInfo['counter'] . ":\n";
            $yaml .= self::TAB . self::TAB . 'ImageSection: ' . $sectionId . "\n";
            $yaml .= self::TAB . self::TAB . 'fileName: ' . $image['Image'] . "\n";
            $yaml .= self::TAB . self::TAB . 'imageType: ' . $imageInfo['type'] . "\n";
            $yaml .= self::TAB . self::TAB . 'useOnSection: ' . $imageInfo['useOnSection'] . "\n";
        }

        return $yaml;
    }

    /**
     * Convert the image text to yaml
     *
     * @return void
     */
    private function _convertSectionImageTextToYaml(array $section)
    {
        $yaml = '';
        $sectionId = $section['sectionId'];

        foreach ($section['Images'] as $image) {
            $imageInfo = $this->_imageFiles[$image['Image']];

            $yaml .= self::TAB . 'imageText' . $this->_imageTextId . ":\n";
            $yaml .= self::TAB . self::TAB . 'ImageFile: ' . 'image' . $imageInfo['counter'] . "\n";
            $yaml .= self::TAB . self::TAB . 'language: fr' . "\n";
            $yaml .= self::TAB . self::TAB . 'description: ' . str_replace("\n", ' ', $image['DescriptionFr']) . "\n";
            $this->_imageTextId++;

            $yaml .= self::TAB . 'imageText' . $this->_imageTextId . ":\n";
            $yaml .= self::TAB . self::TAB . 'ImageFile: ' . 'image' . $imageInfo['counter'] . "\n";
            $yaml .= self::TAB . self::TAB . 'language: en' . "\n";
            $yaml .= self::TAB . self::TAB . 'description: ' . str_replace("\n", ' ', $image['DescriptionEn']) . "\n";
            $this->_imageTextId++;
        }

        return $yaml;
    }






    private $fr = array(

            array(
                'SectionMainImage'          =>  '20091017MichelEtEric.JPG',
                'SectionSmallImage'         =>  '20091017MichelEtEricSmall.JPG',
                'SectionDate'                       =>  '2009-10-17',
                'SectionTitle'                  =>  'S&eacute;minaire ext&eacute;rieur',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '20091017MichelEtPavel.JPG',
                            'Description'           =>  'Michel et Pavel'
                        ),
                        array(
                            'Image'                     =>  '20091017MichelEtEric.JPG',
                            'Description'           =>  'Michel et Eric'
                        ),
                        array(
                            'Image'                     =>  '20091017JeanMarcEtPavel.JPG',
                            'Description'           =>  'Jean Marc et Pavel'
                        ),
                        array(
                            'Image'                     =>  '20091017AviEtJeanMarc.JPG',
                            'Description'           =>  'Avi et Jean Marc'
                        ),

                        array(
                            'Image'                     =>  '20091017MichelEtAvi.JPG',
                            'Description'           =>  'Michel et Avi'
                        ),

                        array(
                            'Image'                     =>  '20091017JeanMarcEtAvi.JPG',
                            'Description'           =>  'Jean Marc et Avi'
                        ),

                        array(
                            'Image'                     =>  '20091017EricEtJeanMarc.JPG',
                            'Description'           =>  'Eric et Jean Marc'
                        ),

                        array(
                            'Image'                     =>  '20091017JeanMarcEtMichel.JPG',
                            'Description'           =>  'Jean Marc et Michel'
                        ),


                        array(
                            'Image'                     =>  '20091017EricEtJeanMarc2.JPG',
                            'Description'           =>  'Eric et Jean Marc'
                        ),


                    ),
            ),



            array(
                'SectionMainImage'          =>  '20090504ToutLeGroupe_1.JPG',
                'SectionSmallImage'         =>  '20090504ToutLeGroupe_1Small.JPG',
                'SectionDate'                       =>  '2009-05-04',
                'SectionTitle'                  =>  'Examens',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '20090504ReneAnneEtYohan.JPG',
                            'Description'           =>  'Ren&eacute;-Anne et Yohan'
                        ),
                        array(
                            'Image'                     =>  '20090504YohanEtEric.JPG',
                            'Description'           =>  'Yohan et Eric'
                        ),
                        array(
                            'Image'                     =>  '20090504PavelEtMichelW.JPG',
                            'Description'           =>  'Pavel Et Michel W.'
                        ),
                        array(
                            'Image'                     =>  '20090504IanEtMichelB.JPG',
                            'Description'           =>  'Ian Et Michel B.'
                        ),

                        array(
                            'Image'                     =>  '20090504EricEtMichelW.JPG',
                            'Description'           =>  'Eric Et Michel W.'
                        ),

                        array(
                            'Image'                     =>  '20090504DavidEtMichelB_1.JPG',
                            'Description'           =>  'David Et Michel B'
                        ),

                        array(
                            'Image'                     =>  '20090504DavidEtMichelB_2.JPG',
                            'Description'           =>  'David Et Michel B'
                        ),

                        array(
                            'Image'                     =>  '20090504DavidEtMichelB_3.JPG',
                            'Description'           =>  'David Et Michel B'
                        ),


                        array(
                            'Image'                     =>  '20090504ToutLeGroupe_1.JPG',
                            'Description'           =>  'Tout Le Groupe'
                        ),

                        array(
                            'Image'                     =>  '20090504ToutLeGroupe_2.JPG',
                            'Description'           =>  'Tout Le Groupe'
                        ),


                    ),
            ),



            array(
                'SectionMainImage'          =>  '20081206ToutLeMonde.JPG',
                'SectionSmallImage'         =>  '20081206ToutLeMondeSmall.JPG',
                'SectionDate'                       =>  '2008-12-06',
                'SectionTitle'                  =>  'Souper de no&euml;l',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '20081206LesEnfants1.JPG',
                            'Description'           =>  'Les enfants 1'
                        ),
                        array(
                            'Image'                     =>  '20081206LesEnfants2.JPG',
                            'Description'           =>  'Les enfants 2'
                        ),
                        array(
                            'Image'                     =>  '20081206LesEtudiants.JPG',
                            'Description'           =>  'Les &eacute;tudiants'
                        ),
                        array(
                            'Image'                     =>  '20081206ToutLeMonde.JPG',
                            'Description'           =>  'Tous'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '20081201PlaqueSensei.JPG',
                'SectionSmallImage'         =>  '20081201PlaqueSenseiSmall.JPG',
                'SectionDate'                       =>  '2008-12-01',
                'SectionTitle'                  =>  'Examens',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '20081201PawelMichelW.JPG',
                            'Description'           =>  'Pawel Vs Michel W'
                        ),
                        array(
                            'Image'                     =>  '20081201PavelMichelW.JPG',
                            'Description'           =>  'Pavel Vs Michel W'
                        ),
                        array(
                            'Image'                     =>  '20081201EricPierreKenjutsu.JPG',
                            'Description'           =>  'Kenjutsu Eric Vs Pierre'
                        ),
                        array(
                            'Image'                     =>  '20081201EricPierre1.JPG',
                            'Description'           =>  'Eric Vs Pierre 1'
                        ),
                        array(
                            'Image'                     =>  '20081201EricPierre2.JPG',
                            'Description'           =>  'Eric Vs Pierre 2'
                        ),
                        array(
                            'Image'                     =>  '20081201PlaqueSensei.JPG',
                            'Description'           =>  "Remise d'une plaque &agrave; Sensei"
                        ),
                    array(
                            'Image'                     =>  '20081201Groupe.JPG',
                            'Description'           =>  'Photo du groupe'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  '20081012Seminaire4.jpg',
                'SectionSmallImage'         =>  '20081012Seminaire4Small.jpg',
                'SectionDate'                       =>  '2008-10-12',
                'SectionTitle'                  =>  'S&eacute;minaire points de pression',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '20081012Seminaire1.jpg',
                            'Description'           =>  'S&eacute;minaire points de pression 1'
                        ),
                        array(
                            'Image'                     =>  '20081012Seminaire2.jpg',
                            'Description'           =>  'S&eacute;minaire points de pression 2'
                        ),
                        array(
                            'Image'                     =>  '20081012Seminaire3.jpg',
                            'Description'           =>  'S&eacute;minaire points de pression 3'
                        ),
                        array(
                            'Image'                     =>  '20081012Seminaire4.jpg',
                            'Description'           =>  'S&eacute;minaire points de pression 4'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '20080604Groupe.JPG',
                'SectionSmallImage'         =>  '20080604GroupeSmall.JPG',
                'SectionDate'                       =>  '2008-06-04',
                'SectionTitle'                  =>  'Examens',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '20080604PawelPavel.JPG',
                            'Description'           =>  'Pawel vs Pavel'
                        ),
                        array(
                            'Image'                     =>  '20080604KamalPhilippe.JPG',
                            'Description'           =>  'Kamal vs Philippe'
                        ),
                        array(
                            'Image'                     =>  '20080604KamalPavel1.JPG',
                            'Description'           =>  'Kamal vs Pavel 1'
                        ),
                        array(
                            'Image'                     =>  '20080604KamalPavel2.JPG',
                            'Description'           =>  'Kamal vs Pavel 2'
                        ),
                        array(
                            'Image'                     =>  '20080604IanEric1.JPG',
                            'Description'           =>  'Ian vs Eric 1'
                        ),
                        array(
                            'Image'                     =>  '20080604IanEric2.JPG',
                            'Description'           =>  'Ian vs Eric 2'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW1.JPG',
                            'Description'           =>  'Michel B vs Michel W 1'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW2.JPG',
                            'Description'           =>  'Michel B vs Michel W 2'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW3.JPG',
                            'Description'           =>  'Michel B vs Michel W 3'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW4.JPG',
                            'Description'           =>  'Michel B vs Michel W 4'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW5.JPG',
                            'Description'           =>  'Michel B vs Michel W 5'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW6.JPG',
                            'Description'           =>  'Michel B vs Michel W 6'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW7.JPG',
                            'Description'           =>  'Michel B vs Michel W 7'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW8.JPG',
                            'Description'           =>  'Michel B vs Michel W 8'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBDavid.JPG',
                            'Description'           =>  'Michel B vs David'
                        ),
                        array(
                            'Image'                     =>  '20080604Groupe.JPG',
                            'Description'           =>  'Photo du groupe'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '20080203LeGroupeSalue.jpg',
                'SectionSmallImage'         =>  '20080203LeGroupeSalueSmall.jpg',
                'SectionDate'                       =>  '2008-02-03',
                'SectionTitle'                  =>  'Examen des enfants',
                'Images'                                =>
                    array(


                        array(
                            'Image'                     =>  '20080203ShahinPrepare.jpg',
                            'Description'           =>  'Shahin se pr&eacute;pare'
                        ),
                        array(
                            'Image'                     =>  '20080203GarielleSabrinaElody.jpg',
                            'Description'           =>  'Gabrielle, Sabrina et Elody'
                        ),
                        array(
                            'Image'                     =>  '20080203Isabelle.jpg',
                            'Description'           =>  'Isabelle se pr&eacute;pare'
                        ),
                        array(
                            'Image'                     =>  '20080203GabrielleEtSabrina.jpg',
                            'Description'           =>  'Gabrielle et Sabrina'
                        ),
                        array(
                            'Image'                     =>  '20080203Amanie.jpg',
                            'Description'           =>  'Amanie se pr&eacute;pare'
                        ),
                        array(
                            'Image'                     =>  '20080203ZacharySePrepare.jpg',
                            'Description'           =>  'Zachary se pr&eacute;pare'
                        ),
                        array(
                            'Image'                     =>  '20080203LeGroupeSalue.jpg',
                            'Description'           =>  'Le groupe salue'
                        ),
                        array(
                            'Image'                     =>  '20080203RechaufementElody.jpg',
                            'Description'           =>  'R&eacute;chaufement Elody'
                        ),
                        array(
                            'Image'                     =>  '20080203Rechaufement.jpg',
                            'Description'           =>  'R&eacute;chaufement'
                        ),
                        array(
                            'Image'                     =>  '20080203ExamenShahin.jpg',
                            'Description'           =>  'Examen de Shahin'
                        ),
                        array(
                            'Image'                     =>  '20080203ExamenSabrinaGabrielle.jpg',
                            'Description'           =>  'Examen Sabrina et Gabrielle'
                        ),
                        array(
                            'Image'                     =>  '20080203ExamenAmanieElody1.jpg',
                            'Description'           =>  'Examen Amanie et Elody 1'
                        ),
                        array(
                            'Image'                     =>  '20080203ExamenAmanieElody2.jpg',
                            'Description'           =>  'Examen Amanie et Elody 2'
                        ),
                        array(
                            'Image'                     =>  '20080203ExamenIsabelle.jpg',
                            'Description'           =>  'Examen Isabelle'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  '20071212KoryukanGroup1.jpg',
                'SectionSmallImage'         =>  '20071212KoryukanGroupSmall.jpg',
                'SectionDate'                       =>  '2007-12-12',
                'SectionTitle'                  =>  'C&eacute;r&eacute;monie de remise des certficats',
                'Images'                                =>
                    array(

                        array(
                            'Image'                     =>  '20071212Hakama.jpg',
                            'Description'           =>  'Comment mettre un Hakama'
                        ),
                        array(
                            'Image'                     =>  '20071212Kamal9thKyu.jpg',
                            'Description'           =>  'Kamal 9i&egrave;me Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Pavel9thKyu.jpg',
                            'Description'           =>  'Pavel 9i&egrave;me Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Philipe9thKyu.jpg',
                            'Description'           =>  'Philippe 9i&egrave;me Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Terry9thKyu.jpg',
                            'Description'           =>  'Terry 9i&egrave;me Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Eric7thKyu.jpg',
                            'Description'           =>  'Eric 7i&egrave;me Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Glenn4thKyu.jpg',
                            'Description'           =>  'Glen 4i&egrave;me Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Pierre4thKyu.jpg',
                            'Description'           =>  'Pierre 4i&egrave;me Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212MichelW3rdKyu.jpg',
                            'Description'           =>  'Michel W. 3i&egrave;me Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Michel1stKyu.jpg',
                            'Description'           =>  'Michel B. 1ier Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212KoryukanGroup1.jpg',
                            'Description'           =>  'Groupe Koryukan 1'
                        ),
                        array(
                            'Image'                     =>  '20071212KoryukanGroup2.jpg',
                            'Description'           =>  'Groupe Koryukan 2'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '20071209Group6.jpg',
                'SectionSmallImage'         =>  '20071209GroupSmall.jpg',
                'SectionDate'                       =>  '2007-12-09',
                'SectionTitle'                  =>  'Souper de no&euml;l',
                'Images'                                =>
                    array(

                        array(
                            'Image'                     =>  '20071209Group1.jpg',
                            'Description'           =>  'Photo de groupe 1'
                        ),
                        array(
                            'Image'                     =>  '20071209Group2.jpg',
                            'Description'           =>  'Photo de groupe 2'
                        ),
                        array(
                            'Image'                     =>  '20071209Group3.jpg',
                            'Description'           =>  'Photo de groupe 3'
                        ),
                        array(
                            'Image'                     =>  '20071209Group4.jpg',
                            'Description'           =>  'Photo de groupe 4'
                        ),
                        array(
                            'Image'                     =>  '20071209Group5.jpg',
                            'Description'           =>  'Photo de groupe 5'
                        ),
                        array(
                            'Image'                     =>  '20071209Group6.jpg',
                            'Description'           =>  'Photo de groupe 6'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '20071128Group.jpg',
                'SectionSmallImage'         =>  '20071128GroupSmall.jpg',
                'SectionDate'                       =>  '2007-11-28',
                'SectionTitle'                  =>  'Examens',
                'Images'                                =>
                    array(

                        array(
                            'Image'                     =>  '20071128KamalPavel1.jpg',
                            'Description'           =>  'Kamal vs Pavel 1'
                        ),
                        array(
                            'Image'                     =>  '20071128KamalPavel2.jpg',
                            'Description'           =>  'Kamal vs Pavel 2'
                        ),
                        array(
                            'Image'                     =>  '20071128PhilippeTerry1.jpg',
                            'Description'           =>  'Philippe vs Terry 1'
                        ),
                        array(
                            'Image'                     =>  '20071128PhilippeTerry2.jpg',
                            'Description'           =>  'Philippe vs Terry 2'
                        ),
                        array(
                            'Image'                     =>  '20071128GlenPierre1.jpg',
                            'Description'           =>  'Glen vs Pierre 1'
                        ),
                        array(
                            'Image'                     =>  '20071128GlenPierre2.jpg',
                            'Description'           =>  'Glen vs Pierre 2'
                        ),
                        array(
                            'Image'                     =>  '20071128MichelBMichelW1.jpg',
                            'Description'           =>  'Michel B vs Michel W 1'
                        ),
                        array(
                            'Image'                     =>  '20071128MichelBMichelW2.jpg',
                            'Description'           =>  'Michel B vs Michel W 2'
                        ),
                        array(
                            'Image'                     =>  '20071128MichelBMichelW3.jpg',
                            'Description'           =>  'Michel B vs Michel W 3'
                        ),
                        array(
                            'Image'                     =>  '20071128MichelBDavid.jpg',
                            'Description'           =>  'Michel B vs David'
                        ),
                        array(
                            'Image'                     =>  '20071128Group.jpg',
                            'Description'           =>  'Photo du groupe'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  'mont32006-010.jpg',
                'SectionSmallImage'         =>  'mont32006-010sm.jpg',
                'SectionDate'                       =>  '2006-03-01',
                'SectionTitle'                  =>  'Entra&icirc;nement conjoint avec le groupe des &Eacute;tats-Unis de James Mullins et Richard Kinville. ',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  'mont32006-010.jpg',
                            'Description'           =>  'Photo 1'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  '100_2079.jpg',
                'SectionSmallImage'         =>  '100_2079sm.jpg',
                'SectionDate'                       =>  '2005-09-01',
                'SectionTitle'                  =>  "Photos de la visite d'Okabayashi Sensei en 2005.",
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '100_2079.jpg',
                            'Description'           =>  'Sensei avec les membres du Hakuho Ryu Montreal '
                        ),
                        array(
                            'Image'                     =>  '100_2070.jpg',
                            'Description'           =>  'Dan Sharp, Joseph Wilson, Medhat Darwish, Nicola Noldin and Quentin Ball'
                        ),
                        array(
                            'Image'                     =>  '100_2049.jpg',
                            'Description'           =>  'Groupe 1'
                        ),
                        array(
                            'Image'                     =>  '100_2054.jpg',
                            'Description'           =>  'Feu de camp 2'
                        ),
                        array(
                            'Image'                     =>  '100_1991.jpg',
                            'Description'           =>  'Petite pause(1)'
                        ),
                        array(
                            'Image'                     =>  '100_1992.jpg',
                            'Description'           =>  'Petite pause(2)'
                        ),
                        array(
                            'Image'                     =>  '100_1997.jpg',
                            'Description'           =>  'Petite pause(3)'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '2004_10_5.jpg',
                'SectionSmallImage'         =>  '2004_10_5sm.jpg',
                'SectionDate'                       =>  '2004-10-01',
                'SectionTitle'                  =>  "Photos de la visite d'Okabayashi Sensei en 2004",
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2004_10_8.jpg',
                            'Description'           =>  'Oakland University Mi'
                        ),
                        array(
                            'Image'                     =>  '2004_10_1.jpg',
                            'Description'           =>  'White oak dojo Mi'
                        ),
                        array(
                            'Image'                     =>  '2004_10_2.jpg',
                            'Description'           =>  'Montreal, s&eacute;minaire ouvert'
                        ),
                        array(
                            'Image'                     =>  '2004_10_3.jpg',
                            'Description'           =>  'Montreal, s&eacute;minaire ferm&eacute;'
                        ),
                        array(
                            'Image'                     =>  '2004_10_4.jpg',
                            'Description'           =>  'Sensei avec les membres du Hakuho Ryu Montreal'
                        ),
                        array(
                            'Image'                     =>  '2004_10_5.jpg',
                            'Description'           =>  'Sensei en pleine d&eacute;monstration'
                        ),
                        array(
                            'Image'                     =>  '2004_10_6.jpg',
                            'Description'           =>  'Sensei avec les membres du Hakuho Ryu Montreal'
                        ),
                        array(
                            'Image'                     =>  '2004_10_7.jpg',
                            'Description'           =>  'Okabayashi Sensei avec Darwish Sensei'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  '2004_08_6.jpg',
                'SectionSmallImage'         =>  '2004_08_6sm.jpg',
                'SectionDate'                       =>  '2004-08-01',
                'SectionTitle'                  =>  'Photos du festival japonais Matsuri, o&ugrave; le Koryukan participa &agrave; une d&eacute;monstration.',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2004_08_1.jpg',
                            'Description'           =>  'Photo 1'
                        ),
                        array(
                            'Image'                     =>  '2004_08_2.jpg',
                            'Description'           =>  'Photo 2'
                        ),
                        array(
                            'Image'                     =>  '2004_08_3.jpg',
                            'Description'           =>  'Photo 3'
                        ),
                        array(
                            'Image'                     =>  '2004_08_4.jpg',
                            'Description'           =>  'Photo 4'
                        ),
                        array(
                            'Image'                     =>  '2004_08_5.jpg',
                            'Description'           =>  'Photo 5'
                        ),
                        array(
                            'Image'                     =>  '2004_08_6.jpg',
                            'Description'           =>  'Photo 6'
                        ),
                        array(
                            'Image'                     =>  '2004_08_7.jpg',
                            'Description'           =>  'Photo 7'
                        ),
                        array(
                            'Image'                     =>  '2004_08_8.jpg',
                            'Description'           =>  'Photo 8'
                        ),
                        array(
                            'Image'                     =>  '2004_08_9.jpg',
                            'Description'           =>  'Photo 9'
                        ),
                        array(
                            'Image'                     =>  '2004_08_10.jpg',
                            'Description'           =>  'Photo 10'
                        ),
                        array(
                            'Image'                     =>  '2004_08_11.jpg',
                            'Description'           =>  'Photo 11'
                        ),
                        array(
                            'Image'                     =>  '2004_08_12.jpg',
                            'Description'           =>  'Photo 12'
                        ),
                        array(
                            'Image'                     =>  '2004_08_13.jpg',
                            'Description'           =>  'Photo 13'
                        ),
                        array(
                            'Image'                     =>  '2004_08_14.jpg',
                            'Description'           =>  'Photo 14'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '2004_06_1.jpg',
                'SectionSmallImage'         =>  '2004_06_1sm.jpg',
                'SectionDate'                       =>  '2004-06-01',
                'SectionTitle'                  =>  "Okabayashi Sensei donna un s&eacute;minaire &agrave; nos coll&egrave;gues en Italie.  Voici quelques photos gracieusement fournies par M. James Mullins, qui s'y rendit des Etats-Unis pour pratiquer avec Sensei.",
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2004_06_1.jpg',
                            'Description'           =>  'Photo 1'
                        ),
                        array(
                            'Image'                     =>  '2004_06_2.jpg',
                            'Description'           =>  'Photo 2',
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '2004_04_3.jpg',
                'SectionSmallImage'         =>  '2004_04_3sm.jpg',
                'SectionDate'                       =>  '2004-04-01',
                'SectionTitle'                  =>  'Photos de la d&eacute;monstration &agrave; Aikido Qu&eacute;bec.',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2004_04_1.jpg',
                            'Description'           =>  'Photo 1'
                        ),
                        array(
                            'Image'                     =>  '2004_04_2.jpg',
                            'Description'           =>  'Photo 2'
                        ),
                        array(
                            'Image'                     =>  '2004_04_3.jpg',
                            'Description'           =>  'Photo 3'
                        ),
                        array(
                            'Image'                     =>  '2004_04_4.jpg',
                            'Description'           =>  'Photo 4'
                        ),
                        array(
                            'Image'                     =>  '2004_04_5.jpg',
                            'Description'           =>  'Photo 5'
                        ),
                        array(
                            'Image'                     =>  '2004_04_6.jpg',
                            'Description'           =>  'Photo 6'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '2004_02_3.jpg',
                'SectionSmallImage'         =>  '2004_02_3sm.jpg',
                'SectionDate'                       =>  '2004-02-01',
                'SectionTitle'                  =>  'Russel Haskin nous fait une demonstration des bons mouvements corporels.',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2004_02_1.jpg',
                            'Description'           =>  'Photo 1'
                        ),
                        array(
                            'Image'                     =>  '2004_02_2.jpg',
                            'Description'           =>  'Photo 2'
                        ),
                        array(
                            'Image'                     =>  '2004_02_3.jpg',
                            'Description'           =>  'Photo 3'
                        ),
                        array(
                            'Image'                     =>  '2004_02_4.jpg',
                            'Description'           =>  'Photo  4'
                        ),
                        array(
                            'Image'                     =>  '2004_02_5.jpg',
                            'Description'           =>  'Photo 5'
                        ),
                        array(
                            'Image'                     =>  '2004_02_6.jpg',
                            'Description'           =>  'Photo 6'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  '2003_11_4.jpg',
                'SectionSmallImage'         =>  '2003_11_4sm.jpg',
                'SectionDate'                       =>  '2003-11-15',
                'SectionTitle'                  =>  "Okabayashi Sensei avec Peter Handley l'assistant, fit la demonstration de plusieurs katas de kenjutsu au s&eacute;minaire de Gloucester, England.",
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2003_11_2.jpg',
                            'Description'           =>  'Photo 1'
                        ),
                        array(
                            'Image'                     =>  '2003_11_3.jpg',
                            'Description'           =>  'Photo 2'
                        ),
                        array(
                            'Image'                     =>  '2003_11_4.jpg',
                            'Description'           =>  'Photo 3'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  '2003_11_1.jpg',
                'SectionSmallImage'         =>  '2003_11_1sm.jpg',
                'SectionDate'                       =>  '2003-11-01',
                'SectionTitle'                  =>  'Un grand merci &agrave; Sensei Raynald Fleury, instructeur chef au Seuin Maru Dojo de nous avoir invite &agrave; participer au s&eacute;minaire du 30 novembre 2003.',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2003_11_1.jpg',
                            'Description'           =>  'Photo '
                        ),
                    ),
            ),




            array(
                'SectionMainImage'          =>  '100_1969.jpg',
                'SectionSmallImage'         =>  '100_1969sm.jpg',
                'SectionDate'                       =>  '2003-01-01',
                'SectionTitle'                  =>  'S&eacute;minaire ouvert avec le Dawson Kentokukan Karate Club',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '100_1969.jpg',
                            'Description'           =>  'Photo  1'
                        ),
                        array(
                            'Image'                     =>  '100_1966.jpg',
                            'Description'           =>  'Photo  2'
                        ),
                        array(
                            'Image'                     =>  '100_1967.jpg',
                            'Description'           =>  'Photo  3'
                        ),
                        array(
                            'Image'                     =>  '100_1980.jpg',
                            'Description'           =>  'Photo  4'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  '2001_10_2.jpg',
                'SectionSmallImage'         =>  '2001_10_2sm.jpg',
                'SectionDate'                       =>  '2001-10-01',
                'SectionTitle'                  =>  'R&eacute;cemment ajout&eacute; des archives: entra&icirc;nement avec Patrick Kell du Hakuho Ryu du Japon',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2001_10_1.jpg',
                            'Description'           =>  'Photo 1'
                        ),
                        array(
                            'Image'                     =>  '2001_10_2.jpg',
                            'Description'           =>  'Photo 2'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  '2002_06_3.jpg',
                'SectionSmallImage'         =>  '2002_06_3sm.jpg',
                'SectionDate'                       =>  '2002-06-01',
                'SectionTitle'                  =>  'Un autre ajout des archives: entra&icirc;nement avec Roben Brown du Hakuho ryu de New York',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2002_06_1.jpg',
                            'Description'           =>  'Photo  1'
                        ),
                        array(
                            'Image'                     =>  '2002_06_2.jpg',
                            'Description'           =>  'Photo 2'
                        ),
                        array(
                            'Image'                     =>  '2002_06_3.jpg',
                            'Description'           =>  'Photo 3'
                        ),
                        array(
                            'Image'                     =>  '2002_06_4.jpg',
                            'Description'           =>  'Photo 4'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  '2003_09_3.jpg',
                'SectionSmallImage'         =>  '2003_09_3sm.jpg',
                'SectionDate'                       =>  '2003-09-01',
                'SectionTitle'                  =>  'Rod Uhler nous rend visite!',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2003_09_1.jpg',
                            'Description'           =>  'Photo 1'
                        ),
                        array(
                            'Image'                     =>  '2003_09_2.jpg',
                            'Description'           =>  'Photo 2'
                        ),
                        array(
                            'Image'                     =>  '2003_09_3.jpg',
                            'Description'           =>  'Photo 3'
                        ),
                        array(
                            'Image'                     =>  '2003_09_4.jpg',
                            'Description'           =>  'Photo 4'
                        ),
                        array(
                            'Image'                     =>  '2003_09_5.jpg',
                            'Description'           =>  'Photo 5'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  '2003_06_1.jpg',
                'SectionSmallImage'         =>  '2003_06_1sm.jpg',
                'SectionDate'                       =>  '2003-06-01',
                'SectionTitle'                  =>  'Seminaire avec Russel Haskin du Hakuho ryu du Japon',
                'Images'                                =>
                    array(

                        array(
                            'Image'                     =>  '2003_06_1.jpg',
                            'Description'           =>  'Photo 1'
                        ),
                        array(
                            'Image'                     =>  '2003_06_2.jpg',
                            'Description'           =>  'Photo 2'
                        ),
                        array(
                            'Image'                     =>  '2003_06_3.jpg',
                            'Description'           =>  'Photo 3'
                        ),
                        array(
                            'Image'                     =>  '2003_06_4.jpg',
                            'Description'           =>  'Photo 4'
                        ),
                        array(
                            'Image'                     =>  '2003_06_5.jpg',
                            'Description'           =>  'Photo 5'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  '2003_05_1.jpg',
                'SectionSmallImage'         =>  '2003_05_1sm.jpg',
                'SectionDate'                       =>  '2003-05-01',
                'SectionTitle'                  =>  'Entra&icirc;nement avec James Mullins et Richard Kinville nous visitant des &eacute;tats-Unis.',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2003_05_1.jpg',
                            'Description'           =>  'Photo 1'
                        ),
                        array(
                            'Image'                     =>  '2003_05_2.jpg',
                            'Description'           =>  'Photo 2'
                        ),
                        array(
                            'Image'                     =>  '2003_05_3.jpg',
                            'Description'           =>  'Photo 3'
                        ),
                        array(
                            'Image'                     =>  '2003_05_4.jpg',
                            'Description'           =>  'Photo 4'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  '2003_02_7.jpg',
                'SectionSmallImage'         =>  '2003_02_7sm.jpg',
                'SectionDate'                       =>  '2003-02-01',
                'SectionTitle'                  =>  'Photos de notre entra&icirc;nement au Japon',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2003_02_1.jpg',
                            'Description'           =>  'Photo 1'
                        ),
                        array(
                            'Image'                     =>  '2003_02_2.jpg',
                            'Description'           =>  'Photo 2'
                        ),
                        array(
                            'Image'                     =>  '2003_02_3.jpg',
                            'Description'           =>  'Photo 3'
                        ),
                        array(
                            'Image'                     =>  '2003_02_4.jpg',
                            'Description'           =>  'Photo 4'
                        ),
                        array(
                            'Image'                     =>  '2003_02_5.jpg',
                            'Description'           =>  'Photo 5'
                        ),
                        array(
                            'Image'                     =>  '2003_02_6.jpg',
                            'Description'           =>  'Photo 6'
                        ),
                        array(
                            'Image'                     =>  '2003_02_7.jpg',
                            'Description'           =>  'Photo 7'
                        ),
                        array(
                            'Image'                     =>  '2003_02_8.jpg',
                            'Description'           =>  'Photo 8'
                        ),
                        array(
                            'Image'                     =>  '2003_02_9.jpg',
                            'Description'           =>  'Photo 9'
                        ),
                        array(
                            'Image'                     =>  '2003_02_10.jpg',
                            'Description'           =>  'Photo 10'
                        ),
                        array(
                            'Image'                     =>  '2003_02_11.jpg',
                            'Description'           =>  'Photo 11'
                        ),
                        array(
                            'Image'                     =>  '2003_02_12.jpg',
                            'Description'           =>  'Photo 12'
                        ),
                        array(
                            'Image'                     =>  '2003_02_13.jpg',
                            'Description'           =>  'Photo 13'
                        ),
                        array(
                            'Image'                     =>  '2003_02_14.jpg',
                            'Description'           =>  'Photo 14'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  'gallery3.jpg',
                'SectionSmallImage'         =>  'gallery3sm.jpg',
                'SectionDate'                       =>  '2002-03-01',
                'SectionTitle'                  =>  'S&eacute;minaire de Daito Ryu Hakuho Kai <br>avec Okabayashi Sensei et Rod Uhler',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  'gallery3.jpg',
                            'Description'           =>  'Photo 1'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  'gallery2.jpg',
                'SectionSmallImage'         =>  'gallery2sm.jpg',
                'SectionDate'                       =>  '2002-03-01',
                'SectionTitle'                  =>  'S&eacute;minaire de Daito Ryu Hakuho Kai',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  'gallery2.jpg',
                            'Description'           =>  'De la gauche : Medhat Darwish, Okabayashi Shogen Sensei, Rod Uhler, Anton Deinekin'
                        ),
                        array(
                            'Image'                     =>  'gallery1.jpg',
                            'Description'           =>  "S'entra&icirc;na avec Okabayashi Sensei"
                        ),
                    ),
            ),

        );

        private $en = array(

            array(
                'SectionMainImage'          =>  '20091017MichelEtEric.JPG',
                'SectionSmallImage'         =>  '20091017MichelEtEricSmall.JPG',
                'SectionDate'                       =>  'Octobre 17 2009',
                'SectionTitle'                  =>  'Outdoor Seminar',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '20091017MichelEtPavel.JPG',
                            'Description'           =>  'Michel and Pavel'
                        ),
                        array(
                            'Image'                     =>  '20091017MichelEtEric.JPG',
                            'Description'           =>  'Michel and Eric'
                        ),
                        array(
                            'Image'                     =>  '20091017JeanMarcEtPavel.JPG',
                            'Description'           =>  'Jean Marc and Pavel'
                        ),
                        array(
                            'Image'                     =>  '20091017AviEtJeanMarc.JPG',
                            'Description'           =>  'Avi and Jean Marc'
                        ),

                        array(
                            'Image'                     =>  '20091017MichelEtAvi.JPG',
                            'Description'           =>  'Michel and Avi'
                        ),

                        array(
                            'Image'                     =>  '20091017JeanMarcEtAvi.JPG',
                            'Description'           =>  'Jean Marc and Avi'
                        ),

                        array(
                            'Image'                     =>  '20091017EricEtJeanMarc.JPG',
                            'Description'           =>  'Eric and Jean Marc'
                        ),

                        array(
                            'Image'                     =>  '20091017JeanMarcEtMichel.JPG',
                            'Description'           =>  'Jean Marc and Michel'
                        ),


                        array(
                            'Image'                     =>  '20091017EricEtJeanMarc2.JPG',
                            'Description'           =>  'Eric and Jean Marc'
                        ),


                    ),
            ),


            array(
                'SectionMainImage'          =>  '20090504ToutLeGroupe_1.JPG',
                'SectionSmallImage'         =>  '20090504ToutLeGroupe_1Small.JPG',
                'SectionDate'                       =>  '04 Mai 2009',
                'SectionTitle'                  =>  'Examens',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '20090504ReneAnneEtYohan.JPG',
                            'Description'           =>  'Ren&eacute;-Anne And Yohan'
                        ),
                        array(
                            'Image'                     =>  '20090504YohanEtEric.JPG',
                            'Description'           =>  'Yohan And Eric'
                        ),
                        array(
                            'Image'                     =>  '20090504PavelEtMichelW.JPG',
                            'Description'           =>  'Pavel And Michel W.'
                        ),
                        array(
                            'Image'                     =>  '20090504IanEtMichelB.JPG',
                            'Description'           =>  'Ian And Michel B.'
                        ),

                        array(
                            'Image'                     =>  '20090504EricEtMichelW.JPG',
                            'Description'           =>  'Eric And Michel W.'
                        ),

                        array(
                            'Image'                     =>  '20090504DavidEtMichelB_1.JPG',
                            'Description'           =>  'David And Michel B'
                        ),

                        array(
                            'Image'                     =>  '20090504DavidEtMichelB_2.JPG',
                            'Description'           =>  'David And Michel B'
                        ),

                        array(
                            'Image'                     =>  '20090504DavidEtMichelB_3.JPG',
                            'Description'           =>  'David And Michel B'
                        ),


                        array(
                            'Image'                     =>  '20090504ToutLeGroupe_1.JPG',
                            'Description'           =>  'Everyone'
                        ),

                        array(
                            'Image'                     =>  '20090504ToutLeGroupe_2.JPG',
                            'Description'           =>  'Everyone'
                        ),


                    ),
            ),

            array(
                'SectionMainImage'          =>  '20081206ToutLeMonde.JPG',
                'SectionSmallImage'         =>  '20081206ToutLeMondeSmall.JPG',
                'SectionDate'                       =>  'Decembre 6 2008',
                'SectionTitle'                  =>  'Christmas Diner',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '20081206LesEnfants1.JPG',
                            'Description'           =>  'The childrens 1'
                        ),
                        array(
                            'Image'                     =>  '20081206LesEnfants2.JPG',
                            'Description'           =>  'The childrens 2'
                        ),
                        array(
                            'Image'                     =>  '20081206LesEtudiants.JPG',
                            'Description'           =>  'The students'
                        ),
                        array(
                            'Image'                     =>  '20081206ToutLeMonde.JPG',
                            'Description'           =>  'Everyone'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '20081201PlaqueSensei.JPG',
                'SectionSmallImage'         =>  '20081201PlaqueSenseiSmall.JPG',
                'SectionDate'                       =>  'December 1st 2008',
                'SectionTitle'                  =>  'Exams',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '20081201PawelMichelW.JPG',
                            'Description'           =>  'Pawel Vs Michel W'
                        ),
                        array(
                            'Image'                     =>  '20081201PavelMichelW.JPG',
                            'Description'           =>  'Pavel Vs Michel W'
                        ),
                        array(
                            'Image'                     =>  '20081201EricPierreKenjutsu.JPG',
                            'Description'           =>  'Kenjutsu Eric Vs Pierre'
                        ),
                        array(
                            'Image'                     =>  '20081201EricPierre1.JPG',
                            'Description'           =>  'Eric Vs Pierre 1'
                        ),
                        array(
                            'Image'                     =>  '20081201EricPierre2.JPG',
                            'Description'           =>  'Eric Vs Pierre 2'
                        ),
                        array(
                            'Image'                     =>  '20081201PlaqueSensei.JPG',
                            'Description'           =>  "Commemorative tablet given to Sensei"
                        ),
                    array(
                            'Image'                     =>  '20081201Groupe.JPG',
                            'Description'           =>  'Group Picture'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  '20081012Seminaire4.jpg',
                'SectionSmallImage'         =>  '20081012Seminaire4Small.jpg',
                'SectionDate'                       =>  'October 12 2008',
                'SectionTitle'                  =>  'Pressure Points Seminar',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '20081012Seminaire1.jpg',
                            'Description'           =>  'Pressure Points Seminar 1'
                        ),
                        array(
                            'Image'                     =>  '20081012Seminaire2.jpg',
                            'Description'           =>  'Pressure Points Seminar 2'
                        ),
                        array(
                            'Image'                     =>  '20081012Seminaire3.jpg',
                            'Description'           =>  'Pressure Points Seminar 3'
                        ),
                        array(
                            'Image'                     =>  '20081012Seminaire4.jpg',
                            'Description'           =>  'Pressure Points Seminar 4'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '20080604Groupe.JPG',
                'SectionSmallImage'         =>  '20080604GroupeSmall.JPG',
                'SectionDate'                       =>  'June 04 2008',
                'SectionTitle'                  =>  'Exams',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '20080604PawelPavel.JPG',
                            'Description'           =>  'Pawel vs Pavel'
                        ),
                        array(
                            'Image'                     =>  '20080604KamalPhilippe.JPG',
                            'Description'           =>  'Kamal vs Philippe'
                        ),
                        array(
                            'Image'                     =>  '20080604KamalPavel1.JPG',
                            'Description'           =>  'Kamal vs Pavel 1'
                        ),
                        array(
                            'Image'                     =>  '20080604KamalPavel2.JPG',
                            'Description'           =>  'Kamal vs Pavel 2'
                        ),
                        array(
                            'Image'                     =>  '20080604IanEric1.JPG',
                            'Description'           =>  'Ian vs Eric 1'
                        ),
                        array(
                            'Image'                     =>  '20080604IanEric2.JPG',
                            'Description'           =>  'Ian vs Eric 2'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW1.JPG',
                            'Description'           =>  'Michel B vs Michel W 1'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW2.JPG',
                            'Description'           =>  'Michel B vs Michel W 2'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW3.JPG',
                            'Description'           =>  'Michel B vs Michel W 3'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW4.JPG',
                            'Description'           =>  'Michel B vs Michel W 4'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW5.JPG',
                            'Description'           =>  'Michel B vs Michel W 5'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW6.JPG',
                            'Description'           =>  'Michel B vs Michel W 6'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW7.JPG',
                            'Description'           =>  'Michel B vs Michel W 7'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBMichelW8.JPG',
                            'Description'           =>  'Michel B vs Michel W 8'
                        ),
                        array(
                            'Image'                     =>  '20080604MichelBDavid.JPG',
                            'Description'           =>  'Michel B vs David'
                        ),
                        array(
                            'Image'                     =>  '20080604Groupe.JPG',
                            'Description'           =>  'Group Picture'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '20080203LeGroupeSalue.jpg',
                'SectionSmallImage'         =>  '20080203LeGroupeSalueSmall.jpg',
                'SectionDate'                       =>  'February 3rd 2008',
                'SectionTitle'                  =>  'Childrens Exams',
                'Images'                                =>
                    array(


                        array(
                            'Image'                     =>  '20080203ShahinPrepare.jpg',
                            'Description'           =>  'Shahin get ready'
                        ),
                        array(
                            'Image'                     =>  '20080203GarielleSabrinaElody.jpg',
                            'Description'           =>  'Gabrielle, Sabrina and Elody'
                        ),
                        array(
                            'Image'                     =>  '20080203Isabelle.jpg',
                            'Description'           =>  'Isabelle get ready'
                        ),
                        array(
                            'Image'                     =>  '20080203GabrielleEtSabrina.jpg',
                            'Description'           =>  'Gabrielle and Sabrina'
                        ),
                        array(
                            'Image'                     =>  '20080203Amanie.jpg',
                            'Description'           =>  'Amanie get ready'
                        ),
                        array(
                            'Image'                     =>  '20080203ZacharySePrepare.jpg',
                            'Description'           =>  'Zachary get ready'
                        ),
                        array(
                            'Image'                     =>  '20080203LeGroupeSalue.jpg',
                            'Description'           =>  'The group bows'
                        ),
                        array(
                            'Image'                     =>  '20080203RechaufementElody.jpg',
                            'Description'           =>  'Elody warms up'
                        ),
                        array(
                            'Image'                     =>  '20080203Rechaufement.jpg',
                            'Description'           =>  'Warm Up'
                        ),
                        array(
                            'Image'                     =>  '20080203ExamenShahin.jpg',
                            'Description'           =>  "Shahin's exam"
                        ),
                        array(
                            'Image'                     =>  '20080203ExamenSabrinaGabrielle.jpg',
                            'Description'           =>  "Sabrina's and Gabrielle's exam"
                        ),
                        array(
                            'Image'                     =>  '20080203ExamenAmanieElody1.jpg',
                            'Description'           =>  "Amanie's and Elody's exam 1"
                        ),
                        array(
                            'Image'                     =>  '20080203ExamenAmanieElody2.jpg',
                            'Description'           =>  "Amanie's and Elody's exam 2"
                        ),
                        array(
                            'Image'                     =>  '20080203ExamenIsabelle.jpg',
                            'Description'           =>  "Isabelle's exam"
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  '20071212KoryukanGroup1.jpg',
                'SectionSmallImage'         =>  '20071212KoryukanGroupSmall.jpg',
                'SectionDate'                       =>  'December 12 2007',
                'SectionTitle'                  =>  'Certification Ceremony',
                'Images'                                =>
                    array(

                        array(
                            'Image'                     =>  '20071212Hakama.jpg',
                            'Description'           =>  'Hakama Trainning'
                        ),
                        array(
                            'Image'                     =>  '20071212Kamal9thKyu.jpg',
                            'Description'           =>  'Kamal 9th Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Pavel9thKyu.jpg',
                            'Description'           =>  'Pavel 9th Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Philipe9thKyu.jpg',
                            'Description'           =>  'Philippe 9th Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Terry9thKyu.jpg',
                            'Description'           =>  'Terry 9i&egrave;me Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Eric7thKyu.jpg',
                            'Description'           =>  'Eric 7th Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Glenn4thKyu.jpg',
                            'Description'           =>  'Glen 4th Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Pierre4thKyu.jpg',
                            'Description'           =>  'Pierre 4th Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212MichelW3rdKyu.jpg',
                            'Description'           =>  'Michel W. 3rd Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212Michel1stKyu.jpg',
                            'Description'           =>  'Michel B. 1st Kyu'
                        ),
                        array(
                            'Image'                     =>  '20071212KoryukanGroup1.jpg',
                            'Description'           =>  'Koryukan Group 1'
                        ),
                        array(
                            'Image'                     =>  '20071212KoryukanGroup2.jpg',
                            'Description'           =>  'Koryukan Group 2'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '20071209Group6.jpg',
                'SectionSmallImage'         =>  '20071209GroupSmall.jpg',
                'SectionDate'                       =>  'December 09 2007',
                'SectionTitle'                  =>  'Christmas Diner',
                'Images'                                =>
                    array(

                        array(
                            'Image'                     =>  '20071209Group1.jpg',
                            'Description'           =>  'Group Picture 1'
                        ),
                        array(
                            'Image'                     =>  '20071209Group2.jpg',
                            'Description'           =>  'Group Picture 2'
                        ),
                        array(
                            'Image'                     =>  '20071209Group3.jpg',
                            'Description'           =>  'Group Picture 3'
                        ),
                        array(
                            'Image'                     =>  '20071209Group4.jpg',
                            'Description'           =>  'Group Picture 4'
                        ),
                        array(
                            'Image'                     =>  '20071209Group5.jpg',
                            'Description'           =>  'Group Picture 5'
                        ),
                        array(
                            'Image'                     =>  '20071209Group6.jpg',
                            'Description'           =>  'Group Picture 6'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '20071128Group.jpg',
                'SectionSmallImage'         =>  '20071128GroupSmall.jpg',
                'SectionDate'                       =>  'November 28 2007',
                'SectionTitle'                  =>  'Exams',
                'Images'                                =>
                    array(

                        array(
                            'Image'                     =>  '20071128KamalPavel1.jpg',
                            'Description'           =>  'Kamal vs Pavel 1'
                        ),
                        array(
                            'Image'                     =>  '20071128KamalPavel2.jpg',
                            'Description'           =>  'Kamal vs Pavel 2'
                        ),
                        array(
                            'Image'                     =>  '20071128PhilippeTerry1.jpg',
                            'Description'           =>  'Philippe vs Terry 1'
                        ),
                        array(
                            'Image'                     =>  '20071128PhilippeTerry2.jpg',
                            'Description'           =>  'Philippe vs Terry 2'
                        ),
                        array(
                            'Image'                     =>  '20071128GlenPierre1.jpg',
                            'Description'           =>  'Glen vs Pierre 1'
                        ),
                        array(
                            'Image'                     =>  '20071128GlenPierre2.jpg',
                            'Description'           =>  'Glen vs Pierre 2'
                        ),
                        array(
                            'Image'                     =>  '20071128MichelBMichelW1.jpg',
                            'Description'           =>  'Michel B vs Michel W 1'
                        ),
                        array(
                            'Image'                     =>  '20071128MichelBMichelW2.jpg',
                            'Description'           =>  'Michel B vs Michel W 2'
                        ),
                        array(
                            'Image'                     =>  '20071128MichelBMichelW3.jpg',
                            'Description'           =>  'Michel B vs Michel W 3'
                        ),
                        array(
                            'Image'                     =>  '20071128MichelBDavid.jpg',
                            'Description'           =>  'Michel B vs David'
                        ),
                        array(
                            'Image'                     =>  '20071128Group.jpg',
                            'Description'           =>  'Group Picture'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  'mont32006-010.jpg',
                'SectionSmallImage'         =>  'mont32006-010sm.jpg',
                'SectionDate'                       =>  'March 2006',
                'SectionTitle'                  =>  'Joint training with the US group of James Mullins and Richard Kinville',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  'mont32006-010.jpg',
                            'Description'           =>  'Picture 1'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  '100_2079.jpg',
                'SectionSmallImage'         =>  '100_2079sm.jpg',
                'SectionDate'                       =>  'September 2005',
                'SectionTitle'                  =>  "Pictures of Okabayashi Sensei's 2005 visit",
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '100_2079.jpg',
                            'Description'           =>  'Sensei w/ Members of Hakuho Ryu Montreal'
                        ),
                        array(
                            'Image'                     =>  '100_2070.jpg',
                            'Description'           =>  'Dan Sharp, Joseph Wilson, Medhat Darwish, Nicola Noldin and Quentin Ball'
                        ),
                        array(
                            'Image'                     =>  '100_2049.jpg',
                            'Description'           =>  'Group 1'
                        ),
                        array(
                            'Image'                     =>  '100_2054.jpg',
                            'Description'           =>  'Fire Camp 2'
                        ),
                        array(
                            'Image'                     =>  '100_1991.jpg',
                            'Description'           =>  'Relaxing between training(1)'
                        ),
                        array(
                            'Image'                     =>  '100_1992.jpg',
                            'Description'           =>  'Relaxing between training(2)'
                        ),
                        array(
                            'Image'                     =>  '100_1997.jpg',
                            'Description'           =>  'Relaxing between training(3)'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '2004_10_5.jpg',
                'SectionSmallImage'         =>  '2004_10_5sm.jpg',
                'SectionDate'                       =>  'October 2004',
                'SectionTitle'                  =>  "Photos of Okabayashi Sensei's 2004 tour",
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2004_10_8.jpg',
                            'Description'           =>  'Oakland University Mi'
                        ),
                        array(
                            'Image'                     =>  '2004_10_1.jpg',
                            'Description'           =>  'White oak dojo Mi'
                        ),
                        array(
                            'Image'                     =>  '2004_10_2.jpg',
                            'Description'           =>  'Montreal open Seminar'
                        ),
                        array(
                            'Image'                     =>  '2004_10_3.jpg',
                            'Description'           =>  'Montreal closed Seminar'
                        ),
                        array(
                            'Image'                     =>  '2004_10_4.jpg',
                            'Description'           =>  'Sensei w/ Members of Hakuho Ryu Montreal'
                        ),
                        array(
                            'Image'                     =>  '2004_10_5.jpg',
                            'Description'           =>  'Sensei demonstrating'
                        ),
                        array(
                            'Image'                     =>  '2004_10_6.jpg',
                            'Description'           =>  'Sensei w/ Members of Hakuho Ryu Montreal'
                        ),
                        array(
                            'Image'                     =>  '2004_10_7.jpg',
                            'Description'           =>  'Okabayashi Sensei w/ Darwish Sensei'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  '2004_08_6.jpg',
                'SectionSmallImage'         =>  '2004_08_6sm.jpg',
                'SectionDate'                       =>  'August 2004',
                'SectionTitle'                  =>  'Photos of the Matsuri Japan festival, at which Koryukan demonstrated',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2004_08_1.jpg',
                            'Description'           =>  'Picture 1'
                        ),
                        array(
                            'Image'                     =>  '2004_08_2.jpg',
                            'Description'           =>  'Picture 2'
                        ),
                        array(
                            'Image'                     =>  '2004_08_3.jpg',
                            'Description'           =>  'Picture 3'
                        ),
                        array(
                            'Image'                     =>  '2004_08_4.jpg',
                            'Description'           =>  'Picture 4'
                        ),
                        array(
                            'Image'                     =>  '2004_08_5.jpg',
                            'Description'           =>  'Picture 5'
                        ),
                        array(
                            'Image'                     =>  '2004_08_6.jpg',
                            'Description'           =>  'Picture 6'
                        ),
                        array(
                            'Image'                     =>  '2004_08_7.jpg',
                            'Description'           =>  'Picture 7'
                        ),
                        array(
                            'Image'                     =>  '2004_08_8.jpg',
                            'Description'           =>  'Picture 8'
                        ),
                        array(
                            'Image'                     =>  '2004_08_9.jpg',
                            'Description'           =>  'Picture 9'
                        ),
                        array(
                            'Image'                     =>  '2004_08_10.jpg',
                            'Description'           =>  'Picture 10'
                        ),
                        array(
                            'Image'                     =>  '2004_08_11.jpg',
                            'Description'           =>  'Picture 11'
                        ),
                        array(
                            'Image'                     =>  '2004_08_12.jpg',
                            'Description'           =>  'Picture 12'
                        ),
                        array(
                            'Image'                     =>  '2004_08_13.jpg',
                            'Description'           =>  'Picture 13'
                        ),
                        array(
                            'Image'                     =>  '2004_08_14.jpg',
                            'Description'           =>  'Picture 14'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '2004_06_1.jpg',
                'SectionSmallImage'         =>  '2004_06_1sm.jpg',
                'SectionDate'                       =>  'June 2004',
                'SectionTitle'                  =>  "Okabayashi Sensei gave a seminar for our fellow practitioners in
                                                                                Italy. Here are a couple of snapshots kindly provided us by Mr.
                                                                                James Mullins, who travelled there from the U.S. to train with Sensei.",
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2004_06_1.jpg',
                            'Description'           =>  'Picture 1'
                        ),
                        array(
                            'Image'                     =>  '2004_06_2.jpg',
                            'Description'           =>  'Picture 2',
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '2004_04_3.jpg',
                'SectionSmallImage'         =>  '2004_04_3sm.jpg',
                'SectionDate'                       =>  'April 2004',
                'SectionTitle'                  =>  "Photos of April's demonstration at Aikido Qu&eacute;bec",
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2004_04_1.jpg',
                            'Description'           =>  'Picture 1'
                        ),
                        array(
                            'Image'                     =>  '2004_04_2.jpg',
                            'Description'           =>  'Picture 2'
                        ),
                        array(
                            'Image'                     =>  '2004_04_3.jpg',
                            'Description'           =>  'Picture 3'
                        ),
                        array(
                            'Image'                     =>  '2004_04_4.jpg',
                            'Description'           =>  'Picture 4'
                        ),
                        array(
                            'Image'                     =>  '2004_04_5.jpg',
                            'Description'           =>  'Picture 5'
                        ),
                        array(
                            'Image'                     =>  '2004_04_6.jpg',
                            'Description'           =>  'Picture 6'
                        ),
                    ),
            ),


            array(
                'SectionMainImage'          =>  '2004_02_3.jpg',
                'SectionSmallImage'         =>  '2004_02_3sm.jpg',
                'SectionDate'                       =>  'February 2004',
                'SectionTitle'                  =>  'Russel Haskin shows us proper body movement',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2004_02_1.jpg',
                            'Description'           =>  'Picture 1'
                        ),
                        array(
                            'Image'                     =>  '2004_02_2.jpg',
                            'Description'           =>  'Picture 2'
                        ),
                        array(
                            'Image'                     =>  '2004_02_3.jpg',
                            'Description'           =>  'Picture 3'
                        ),
                        array(
                            'Image'                     =>  '2004_02_4.jpg',
                            'Description'           =>  'Picture  4'
                        ),
                        array(
                            'Image'                     =>  '2004_02_5.jpg',
                            'Description'           =>  'Picture 5'
                        ),
                        array(
                            'Image'                     =>  '2004_02_6.jpg',
                            'Description'           =>  'Picture 6'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  '2003_11_4.jpg',
                'SectionSmallImage'         =>  '2003_11_4sm.jpg',
                'SectionDate'                       =>  'November 2003',
                'SectionTitle'                  =>  "Okabayashi Sensei--with Peter Handley assisting--covered several sword kata at the seminar in Gloucester, England",
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2003_11_2.jpg',
                            'Description'           =>  'Picture 1'
                        ),
                        array(
                            'Image'                     =>  '2003_11_3.jpg',
                            'Description'           =>  'Picture 2'
                        ),
                        array(
                            'Image'                     =>  '2003_11_4.jpg',
                            'Description'           =>  'Picture 3'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  '2003_11_1.jpg',
                'SectionSmallImage'         =>  '2003_11_1sm.jpg',
                'SectionDate'                       =>  'November 2003',
                'SectionTitle'                  =>  "Special thanks to Sensei Raynald Fleury, chief instructor of the
                                                                                Seuin Maru Dojo for inviting us to participate in a seminar on the
                                                                                30th of November 2003",
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2003_11_1.jpg',
                            'Description'           =>  'Picture 1'
                        ),
                    ),
            ),




            array(
                'SectionMainImage'          =>  '100_1969.jpg',
                'SectionSmallImage'         =>  '100_1969sm.jpg',
                'SectionDate'                       =>  'January 2003',
                'SectionTitle'                  =>  'Open seminar with Dawson Kentokukan Karate Club',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '100_1969.jpg',
                            'Description'           =>  'Picture  1'
                        ),
                        array(
                            'Image'                     =>  '100_1966.jpg',
                            'Description'           =>  'Picture  2'
                        ),
                        array(
                            'Image'                     =>  '100_1967.jpg',
                            'Description'           =>  'Picture  3'
                        ),
                        array(
                            'Image'                     =>  '100_1980.jpg',
                            'Description'           =>  'Picture  4'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  '2001_10_2.jpg',
                'SectionSmallImage'         =>  '2001_10_2sm.jpg',
                'SectionDate'                       =>  'October 2001',
                'SectionTitle'                  =>  'Just added from the archives: Training session with Patrick Kell from Hakuho Ryu, Japan',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2001_10_1.jpg',
                            'Description'           =>  'Picture 1'
                        ),
                        array(
                            'Image'                     =>  '2001_10_2.jpg',
                            'Description'           =>  'Picture 2'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  '2002_06_3.jpg',
                'SectionSmallImage'         =>  '2002_06_3sm.jpg',
                'SectionDate'                       =>  'June 2002',
                'SectionTitle'                  =>  'Another new import from the archives: Training session with Roben Brown, Hakuho ryu, New York',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2002_06_1.jpg',
                            'Description'           =>  'Picture  1'
                        ),
                        array(
                            'Image'                     =>  '2002_06_2.jpg',
                            'Description'           =>  'Picture 2'
                        ),
                        array(
                            'Image'                     =>  '2002_06_3.jpg',
                            'Description'           =>  'Picture 3'
                        ),
                        array(
                            'Image'                     =>  '2002_06_4.jpg',
                            'Description'           =>  'Picture 4'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  '2003_09_3.jpg',
                'SectionSmallImage'         =>  '2003_09_3sm.jpg',
                'SectionDate'                       =>  'September 2003',
                'SectionTitle'                  =>  'Rod Uhler visits!',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2003_09_1.jpg',
                            'Description'           =>  'Picture 1'
                        ),
                        array(
                            'Image'                     =>  '2003_09_2.jpg',
                            'Description'           =>  'Picture 2'
                        ),
                        array(
                            'Image'                     =>  '2003_09_3.jpg',
                            'Description'           =>  'Picture 3'
                        ),
                        array(
                            'Image'                     =>  '2003_09_4.jpg',
                            'Description'           =>  'Picture 4'
                        ),
                        array(
                            'Image'                     =>  '2003_09_5.jpg',
                            'Description'           =>  'Picture 5'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  '2003_06_1.jpg',
                'SectionSmallImage'         =>  '2003_06_1sm.jpg',
                'SectionDate'                       =>  'June 2003',
                'SectionTitle'                  =>  'Seminar with Russel Haskin from Hakuho ryu Japan',
                'Images'                                =>
                    array(

                        array(
                            'Image'                     =>  '2003_06_1.jpg',
                            'Description'           =>  'Picture 1'
                        ),
                        array(
                            'Image'                     =>  '2003_06_2.jpg',
                            'Description'           =>  'Picture 2'
                        ),
                        array(
                            'Image'                     =>  '2003_06_3.jpg',
                            'Description'           =>  'Picture 3'
                        ),
                        array(
                            'Image'                     =>  '2003_06_4.jpg',
                            'Description'           =>  'Picture 4'
                        ),
                        array(
                            'Image'                     =>  '2003_06_5.jpg',
                            'Description'           =>  'Picture 5'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  '2003_05_1.jpg',
                'SectionSmallImage'         =>  '2003_05_1sm.jpg',
                'SectionDate'                       =>  'May 2003',
                'SectionTitle'                  =>  'Training session with special guests James Mullins and Richard Kinville visiting from the U.S',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2003_05_1.jpg',
                            'Description'           =>  'Picture 1'
                        ),
                        array(
                            'Image'                     =>  '2003_05_2.jpg',
                            'Description'           =>  'Picture 2'
                        ),
                        array(
                            'Image'                     =>  '2003_05_3.jpg',
                            'Description'           =>  'Picture 3'
                        ),
                        array(
                            'Image'                     =>  '2003_05_4.jpg',
                            'Description'           =>  'Picture 4'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  '2003_02_7.jpg',
                'SectionSmallImage'         =>  '2003_02_7sm.jpg',
                'SectionDate'                       =>  'F&eacute;vrier 2003',
                'SectionTitle'                  =>  'Photos of Training In Japan',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  '2003_02_1.jpg',
                            'Description'           =>  'Picture 1'
                        ),
                        array(
                            'Image'                     =>  '2003_02_2.jpg',
                            'Description'           =>  'Picture 2'
                        ),
                        array(
                            'Image'                     =>  '2003_02_3.jpg',
                            'Description'           =>  'Picture 3'
                        ),
                        array(
                            'Image'                     =>  '2003_02_4.jpg',
                            'Description'           =>  'Picture 4'
                        ),
                        array(
                            'Image'                     =>  '2003_02_5.jpg',
                            'Description'           =>  'Picture 5'
                        ),
                        array(
                            'Image'                     =>  '2003_02_6.jpg',
                            'Description'           =>  'Picture 6'
                        ),
                        array(
                            'Image'                     =>  '2003_02_7.jpg',
                            'Description'           =>  'Picture 7'
                        ),
                        array(
                            'Image'                     =>  '2003_02_8.jpg',
                            'Description'           =>  'Picture 8'
                        ),
                        array(
                            'Image'                     =>  '2003_02_9.jpg',
                            'Description'           =>  'Picture 9'
                        ),
                        array(
                            'Image'                     =>  '2003_02_10.jpg',
                            'Description'           =>  'Picture 10'
                        ),
                        array(
                            'Image'                     =>  '2003_02_11.jpg',
                            'Description'           =>  'Picture 11'
                        ),
                        array(
                            'Image'                     =>  '2003_02_12.jpg',
                            'Description'           =>  'Picture 12'
                        ),
                        array(
                            'Image'                     =>  '2003_02_13.jpg',
                            'Description'           =>  'Picture 13'
                        ),
                        array(
                            'Image'                     =>  '2003_02_14.jpg',
                            'Description'           =>  'Picture 14'
                        ),
                    ),
            ),



            array(
                'SectionMainImage'          =>  'gallery3.jpg',
                'SectionSmallImage'         =>  'gallery3sm.jpg',
                'SectionDate'                       =>  'March 2002',
                'SectionTitle'                  =>  'Daito Ryu Hakuho Kai Seminar with Okabayashi Sensei and Rod Uhler',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  'gallery3.jpg',
                            'Description'           =>  'Picture 1'
                        ),
                    ),
            ),

            array(
                'SectionMainImage'          =>  'gallery2.jpg',
                'SectionSmallImage'         =>  'gallery2sm.jpg',
                'SectionDate'                       =>  'March 2002',
                'SectionTitle'                  =>  'Daito Ryu Hakuho Kai Seminar',
                'Images'                                =>
                    array(
                        array(
                            'Image'                     =>  'gallery2.jpg',
                            'Description'           =>  'From the left: Medhat Darwish, Okabayashi Shogen Sensei, Rod Uhler, Anton Deinekin'
                        ),
                        array(
                            'Image'                     =>  'gallery1.jpg',
                            'Description'           =>  "training with Okabayashi Sensei"
                        ),
                    ),
            ),

        );
}