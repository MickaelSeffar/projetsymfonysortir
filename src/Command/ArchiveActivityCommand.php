<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ArchiveActivityCommand extends Command
{
    protected static $defaultName = 'app:archive-activity';
    protected static $defaultDescription = 'Archive les activités datant de plus de 30j';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(string $name = null, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $activityStatus = $this->entityManager->getRepository('App:Activity')->archiveActivity();
        foreach ($activityStatus as $activity){
            $activity->setActive(false);
            $io->writeln("Etat changé pour l'activité".$activity->getId());

        }
        $this->entityManager->flush();

        $io->success("Yo tout le monde c'est mickmick");
        return 0;
    }
}
