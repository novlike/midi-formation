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
    /**
     * @var Factory $lockFactory
     */
    private $lockFactory;

    /**
     * @var string rootDir
     */
    private $dir;

    public function __construct(Factory $lockFactory)
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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        echo 'execute'.PHP_EOL;
        $lock = $this->lockFactory->createLock('lock');

        while (true) {
            if ($lock->acquire()) {
                echo 'lock acquired'.PHP_EOL;
                $finder = new Finder();
                $finder->name('*.txt')->depth(0)->files()->in($this->dir);

                foreach ($finder as $file) {
                    $output->write($file->getFilename());
                    $output->write(PHP_EOL);
                    sleep(3);
                }
                $lock->release();
                echo 'release lock'.PHP_EOL;
                break;
            } else {
                echo 'lock occupied'.PHP_EOL;
                sleep(1);
            }
        }
    }
}
