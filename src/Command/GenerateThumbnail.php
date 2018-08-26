<?php

namespace App\Command;

use App\Manager\PoiManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;

class GenerateThumbnail extends Command
{
    const IMG_DIR = 'public/uploads/document/';

    private $poiManager;
    private $kernel;
    private $thumbnailname;

    public function __construct(PoiManager $poiManager, KernelInterface $kernel)
    {
        $this->poiManager = $poiManager;
        $this->kernel = $kernel;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:generate-thumbnail')
            ->setDescription('Generate thumbail')
            ->setHelp('This command allows you to generate thumbnail :\n
            first argument : thumbnail name : thumb_350_260\n
            second argument : image path\n')
        ;

        $this
            ->addArgument('thumbnailname', InputArgument::REQUIRED, 'Thumbnail name : thumb_350_260')
            ->addArgument('imagepath', InputArgument::OPTIONAL, 'Image path.')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Generate thumbnail',
            '==================',
            '',
        ]);

        $this->thumbnailname = $input->getArgument('thumbnailname');
        $imagePath = $input->getArgument('imagepath');

        if ($imagePath) {
            $this->generateThumbail($imagePath);
        } else {
            $finder = new Finder();
            $finder->files()->in(self::IMG_DIR);

            foreach ($finder as $file) {
                $filePath = 'uploads/document/'.$file->getRelativePathname();
                $output->writeln($this->generateThumbail($filePath));
            }
        }

        $output->writeln('Import done: '.$this->thumbnailname);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function generateThumbail($path)
    {
        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();

        try {
            $application = new Application($this->kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput([
                'command' => 'liip:imagine:cache:resolve',
                'paths' => [$path],
                '--filters' => [$this->thumbnailname],
            ]);

            $application->run($input, $output);

            $content = $output->fetch();

            return $content;
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            $output->writeln('Error: '.$path);
        }
    }
}
