<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Category\Classes;

use Arikaim\Core\Utils\Factory;
use Arikaim\Core\Utils\Utils;
use Arikaim\Core\Utils\Text;
use Exception;

/**
 * Category  
*/
class Category
{
    /**
     * Translate category
     *
     * @param object $model
     * @param string $language
     * @throws Exception
     * @return bool
     */
    public static function translate($model, string $language): bool 
    {
        $translation = $model->translation($language);
        if ($translation !== false) {
            // translation exists
            return false;
        }

        $transalte = Factory::createInstance('Classes\\Translations',[],'translations');
        if (empty($transalte) == true) {
            // translations extension not installed
            throw new Exception('Translations extension not installed');
            return false;
        }

        // get english translation
        $defaultTranslation = $model->translation('en');
        if ($defaultTranslation === false) { 
            // missing default translation
            return false;
        }

        $translated = $transalte->traslateArray('title,description',$defaultTranslation->toArray(),$language);  
        if ($translated === false) {
            // error translation
            return false;
        }

        $translated['title'] = Text::ucFirstUtf($translated['title']);
        if (empty($translated['title']) == false) {
            $translatedFields['slug'] = Utils::slug($translated['title']);                              
        }

        return (bool)$model->saveTranslation($translated,$language);                                                                                                                           
    }
}
