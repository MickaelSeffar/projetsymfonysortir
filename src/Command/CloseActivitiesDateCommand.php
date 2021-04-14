<?php

namespace App\Command;

use App\Entity\State;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CloseActivitiesDateCommand extends Command
{
    protected static $defaultName = 'app:close-activities-date';
    protected static $defaultDescription = "Ferme les activités dont la date est dépassée";

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


        $closeActivity = $this->entityManager->getRepository('App:Activity')->closeActivity();
        $closeState= $entityManager->getRepository(State::class)->findOneBy(['name'=>'Fermé']);
        foreach ($closeActivity as $activity){
            $activity->setState($closeState);

            $io->writeln("Etat fermé pour l'activité".$activity->getId());
        }



        $io->success("Yo tout le monde c'est encore Mickmick");

        return 0;
    }
}
