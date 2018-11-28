<?php
/**
 * Created by PhpStorm.
 * User: hlecouey
 * Date: 28/11/2018
 * Time: 18:16
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Lock\Factory;

class ImportProductTxtCommand extends Command
{
    /**
     * @var Factory $factory
     */
    private $factory;

    /** @var string $dir */
    private $dir;

    public function __construct(Factory $factory)
    {
        parent::__construct();
        $this->factory = $factory;
        $this->dir = __DIR__.'/../../..';
    }

    public function configure()
    {
        $this->setName('app:import:product');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        echo 'Lock created' . PHP_EOL;

        $finder = new Finder();
        $finder->name('*.txt')->depth(0)->in($this->dir)->files();

        foreach ($finder as $file) {
            $lock = $this->factory->createLock('lock' .$file, 30);
            sleep(3);
            if ($lock->acquire(false)) {
                echo  'lock occupied by' .$file.PHP_EOL;
            }
        }
    }
}
