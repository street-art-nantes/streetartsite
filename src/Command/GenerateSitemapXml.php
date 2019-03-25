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

class GenerateSitemapXml extends Command
{
    const SITEMAP_DIR = 'public/sitemap.xml';

    private $poiManager;
    private $kernel;
    private $sitemapDomDoc;

    public function __construct(PoiManager $poiManager, KernelInterface $kernel)
    {
        $this->poiManager = $poiManager;
        $this->kernel = $kernel;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:generate-sitemap')
            ->setDescription('Generate sitemap')
            ->setHelp('This command generate the file sitemap.xml')
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
            'Generate sitemap',
            '==================',
            '',
        ]);

        $this->sitemapDomDoc = new \DOMDocument('1.0', 'utf-8');
        $urlsetNode = $this->sitemapDomDoc->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset');
        $this->sitemapDomDoc->appendChild($urlsetNode);

        // TODO récupérer/créer tous les urls
        $websiteUrls = [];

        $this->getArtworkUrls();
        $this->getArtistUrls();
        $this->getSearchUrls();
        $this->getOtherUrls();


        // Ajout des URLs dans le document XML
        foreach($websiteUrls as $websiteUrl){
            $urlNode = $this->sitemapDomDoc->createElement('url');
            $urlsetNode->appendChild($urlNode);
            $locNode = $this->sitemapDomDoc->createElement('loc', $websiteUrl);
            $urlNode->appendChild($locNode);
        }

        file_put_contents(self::SITEMAP_DIR, $this->sitemapDomDoc->saveXml());

        $output->writeln('Sitemap generation done.');
    }
}
