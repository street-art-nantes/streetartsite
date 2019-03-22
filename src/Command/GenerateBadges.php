<?php

namespace App\Command;

use App\Service\BadgesGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class GenerateBadges extends ContainerAwareCommand
{
    private $kernel;
    private $manager;
    private $badgesGenerator;

    public function __construct(KernelInterface $kernel, EntityManagerInterface $manager, BadgesGenerator $badgesGenerator)
    {
        $this->kernel = $kernel;
        $this->manager = $manager;
        $this->badgesGenerator = $badgesGenerator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:generate-badges')
            ->setDescription('Generate user level badges')
            ->setHelp('This command allows you to generate user level badges.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Generate badges',
            '============',
            '',
        ]);

        // TODO call service badges generator
        $this->badgesGenerator->badgesGenerator();

//        $viewId = $this->getContainer()->getParameter('google_analytics_view_id');
//
//        // truncate table stats_analytics before import new
//        $classMetaData = $this->manager->getClassMetadata('App\Entity\PageStat');
//        $connection = $this->manager->getConnection();
//        try {
//            $dbPlatform = $connection->getDatabasePlatform();
//            $connection->beginTransaction();
//            $query = $dbPlatform->getTruncateTableSql($classMetaData->getTableName());
//            $connection->executeUpdate($query);
//            $connection->commit();
//        } catch (\Exception $e) {
//            try {
//                $output->writeln($e->getMessage());
//                $output->writeln('Error when truncated table');
//                $connection->rollback();
//            } catch (\Exception $e) {
//                $output->writeln($e->getMessage());
//                $output->writeln('Error in rollback truncated table');
//            } finally {
//                return;
//            }
//        }

        $output->writeln('Generate badges done');
    }
}
