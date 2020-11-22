<?php


namespace App\Service\BankStatement;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Finder
{
    private $bag;

    /**
     * Finder constructor.
     * @param  \Symfony\Component\Finder\Finder  $finder
     * @param  ParameterBagInterface  $bag
     */
    public function __construct( ParameterBagInterface $bag)
    {
        $this->bag = $bag;
    }

    public function getCsvFiles(): array
    {
        $baseDir = $this->bag->get('kernel.project_dir');
        $csvDir = $baseDir . '/var/uploads';

        $finder = new \Symfony\Component\Finder\Finder();
        $files = [];

        $finder->files()->in($csvDir);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $files[] = $file->getRealPath();
            };
        }

        return $files;
    }
}