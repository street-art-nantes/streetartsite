<?php
namespace App\Command;

use App\Manager\PoiManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ImportPoi extends Command
{
    const PATH = '/../var/assets/';

    private $poiManager;
    private $kernel;

    public function __construct(PoiManager $poiManager, KernelInterface $kernel)
    {
        $this->poiManager = $poiManager;
        $this->kernel = $kernel;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:import-poi')
            ->setDescription('Import csv file of POI.')
            ->setHelp('This command allows you to import csv file or POI on this format :\n
            fichier - lat - lng - ville - pays - created_date - type - title - adresse')
        ;

        $this
            ->addArgument('filename', InputArgument::REQUIRED, 'The filename to import.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln([
            'Import POI',
            '============',
            '',
        ]);

        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $datas = $serializer->decode(file_get_contents(
            $this->kernel->getRootDir().
            self::PATH.
            $input->getArgument('filename')), 'csv'
        );

        foreach ($datas as $data) {
            try {
                $this->poiManager->create($data);
            } catch (\Exception $e) {
                $output->writeln('Error: '.json_encode($data));
            }
        }

        $output->writeln('Import done: '.$input->getArgument('filename'));
    }
}