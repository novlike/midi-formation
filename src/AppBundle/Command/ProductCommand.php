<?php
/**
 * Created by PhpStorm.
 * User: hlecouey
 * Date: 19/11/2018
 * Time: 13:23
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Lock\Factory;

class ProductCommand extends Command
{
    private $lockFactory;

    private $dir;

    public function __construct(Factory $lockFactory, array $dir = null)
    {
        $this->lockFactory = $lockFactory;
        $this->dir = __DIR__ . '/../../..';
        parent::__construct();
    }

    public function configure()
    {
        $this->setName('app:import:product')
             ->setDescription('test symfony component lock');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        echo 'execute'.PHP_EOL;
        $lock = $this->lockFactory->createLock('verrou');
        while (true) {
            if ($lock->acquire()) {
                echo 'verrou acquis'.PHP_EOL;
                $finder = new Finder();
                $finder->name('*.txt')->depth(0)->files()->in($this->dir);

                foreach ($finder as $file) {
                    $output->write($file->getFilename());
                    $output->write(PHP_EOL);
                    sleep(3);
                }
                echo 'libération du verrou'.PHP_EOL;
                $lock->release();
                break;
            } else {
                echo 'lock oqp'.PHP_EOL;
                sleep(1);
            }
        }
    }
}
