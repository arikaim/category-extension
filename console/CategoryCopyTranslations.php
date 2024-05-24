<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
 */
namespace Arikaim\Extensions\Category\Console;

use Arikaim\Core\Console\ConsoleCommand;
use Arikaim\Core\Db\Model;
use Arikaim\Core\Collection\Arrays;

/**
 * Delete copy slug & ttile from translations command
 */
class CategoryCopyTranslations extends ConsoleCommand
{  
    /**
     * Configure command
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName('category:copy:translations')
            ->setDescription('Copy sluyg & title from translation.'); 
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function executeCommand($input, $output)
    {               
        $this->showTitle();

        $model = Model::Category('category');
        $translations = Model::CategoryTranslations('category')->all();

        $this->writeFieldLn('Total ',$translations->count());

        foreach ($translations as $item) {
            $this->writeFieldLn('Translation',$item->slug);   
            
            if (empty($item->slug) == false) {
                $category = $model->findById($item->category_id);
                $category->update([
                    'slug'  => $item->slug,
                    'title' => $item->title
                ]);
            }
        }

        $this->showCompleted();
    }
}
