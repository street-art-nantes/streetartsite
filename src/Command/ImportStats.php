<?php

namespace App\Command;

use App\Entity\PageStat;
use Doctrine\ORM\EntityManagerInterface;
use MediaFigaro\GoogleAnalyticsApi\Service\GoogleAnalyticsService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ImportStats extends ContainerAwareCommand
{
    const PATH = '/../var/assets/';

    private $googleAnalyticsApi;
    private $kernel;
    private $manager;

    public function __construct(GoogleAnalyticsService $googleAnalyticsApi, KernelInterface $kernel,
                                EntityManagerInterface $manager)
    {
        $this->googleAnalyticsApi = $googleAnalyticsApi;
        $this->kernel = $kernel;
        $this->manager = $manager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:import-stats')
            ->setDescription('Import stats from Google Analytics.')
            ->setHelp('This command allows you to import stats from Google Analytics via API :\n
            fields : views / path')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Import STATS',
            '============',
            '',
        ]);

        $viewId = $this->getContainer()->getParameter('google_analytics_view_id');

        $data = $this->googleAnalyticsApi->getDataDateRangeMetricsDimensions(
            $viewId,
            '2018-10-01',
            'today',
            ['pageviews'],
            ['pagePath'],
            [
                'fields' => ['pageviews'],
                'order' => 'descending',
            ]
        );

        // truncate table stats_analytics before import new
        $classMetaData = $this->manager->getClassMetadata('App\Entity\PageStat');
        $connection = $this->manager->getConnection();
        try {
            $dbPlatform = $connection->getDatabasePlatform();
            $connection->beginTransaction();
            $query = $dbPlatform->getTruncateTableSql($classMetaData->getTableName());
            $connection->executeUpdate($query);
            $connection->commit();
        } catch (\Exception $e) {
            try {
                $output->writeln($e->getMessage());
                $output->writeln('Error when truncated table');
                $connection->rollback();
            } catch (\Exception $e) {
                $output->writeln($e->getMessage());
                $output->writeln('Error in rollback truncated table');
            } finally {
                return;
            }
        }

        try {
            foreach ($data as $page) {
                $pageStat = new PageStat();
                $pageStat->setViews($page['metrics']['pageviews']);
                $pageStat->setPath($page['dimensions']['pagePath']);
                $this->manager->persist($pageStat);
            }

            $this->manager->flush();
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            $output->writeln('Error: '.json_encode($data));
        }

        $output->writeln('Import stats done');
    }
}
