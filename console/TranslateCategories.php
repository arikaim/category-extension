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
use Arikaim\Core\Utils\Factory;
use Arikaim\Core\Console\ConsoleHelper;
use Arikaim\Extensions\Category\Classes\Category;

/**
 * Translate categories command
 */
class TranslateCategories extends ConsoleCommand
{  
    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('category:translate')->setDescription('Translate categories.'); 
        $this->addOptionalArgument('language','Language code');
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

        $language = $input->getArgument('language');
        if (empty($language) == true) {
            $this->showError('Languge code required');
            return;
        }
        $transalte = Factory::createInstance('Classes\\Translations',[],'translations');
        if (empty($transalte) == true) {
            $this->showError('Translations extension not installed.');
            return;
        }

        $category = Model::Category('category')->all();

        $this->writeFieldLn('Total',$category->count());

        $translated = 0;
        foreach ($category as $item) {
            $result = Category::translate($item,$language);
            if ($result === false) {
                $mark = ConsoleHelper::errorMark();
            } else {
                $mark = ConsoleHelper::checkMark();
                $translated++;
            }
           
            $this->writeLn($mark . ' ' . $item->title);            
        }
        $this->writeFieldLn('Translated',$translated);
        
        $this->showCompleted();
    }
}
