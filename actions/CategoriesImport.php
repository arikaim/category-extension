<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Category\Actions;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Actions\Action;
use Arikaim\Core\Utils\File;

/**
* Categories import from Json file action
*/
class CategoriesImport extends Action 
{
    /**
     * Init action
     *
     * @return void
    */
    public function init(): void
    {
    }

    /**
     * Run action
     *
     * @param mixed ...$params
     * @return bool
     */
    public function run(...$params)
    {
        global $arikaim;

        $branch = $this->getOption('branch',null);

        $fileName = $this->getOption('file_name');
        if (empty($fileName) == true) {
            $this->error("File name not set!");
            return false;
        }

        $content = $arikaim->get('storage')->read($fileName);
        if ($content === false) {
            if (File::exists($fileName) == false) {
                $this->error("Not valid file!");
                return false;
            }
            $content = File::read($fileName);
        }   

        $items = \json_decode($content,true);
        
        $this->import($items,null,$branch);

        return ($this->hasError() == false);
    }

    /**
     * Import categories
     *
     * @param array        $items
     * @param integer|null $parentId
     * @param string|null  $branch
     * @return void
     */
    protected function import(array $items, ?int $parentId = null, ?string $branch = null)
    {
        $model = Model::Category('category');

        $imported = 0;
        foreach ($items as $title => $subItems) {
            $subItems = (\is_array($subItems) == false) ? [] : $subItems;

            if ($model->hasCategory($title,$parentId,$branch) == false) {      
                $model = $model->create([
                    'parent_id' => $parentId,
                    'title'     => $title,
                    'branch'    => $branch
                ]);

                if ($model !== null) {
                    $imported += 1;   
                }
                if (count($subItems) > 0) {
                    $this->import($subItems,$model->id,$branch);
                }
            } 
        }

        $this->result('imported',$this->get('imported',0) + $imported);
    }

    /**
    * Init descriptor properties 
    *
    * @return void
    */
    protected function initDescriptor(): void
    {
    }
}
