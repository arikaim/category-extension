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
 * Delete categories command
 */
class CategoryDelete extends ConsoleCommand
{  
    /**
     * Configure command
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('category:delete')->setDescription('Delete categories.'); 
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

        $category = Model::Category('category')->all();
       
        $this->writeFieldLn('Total',$category->count());

        $deleted = 0;
        foreach ($category as $item) {
            $this->writeFieldLn('Category',Arrays::toString($item->getTitle()));   
            
            try {
                if ($item->remove($item->id) == true) {
                    $deleted++;
                }
            } catch (\Exception $e) {
                $this->showError($e->getMessage());
            }
        }

        $this->writeFieldLn('Deleted',$deleted);
        $this->showCompleted();
    }
}
