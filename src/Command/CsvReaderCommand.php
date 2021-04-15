<?php


namespace App\Command;


use App\Entity\Campus;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CsvReaderCommand
 * @package App\Command
 */
class CsvReaderCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CsvReaderCommand constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('csv:import')
            ->setDescription('Import d\'un fichier CSV');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Attempting import of feed...');

        $reader = Reader::createFromPath('%%kernel.root_dir%/../public/uploads/csv/DATA.csv');
        $results = $reader->fetchAssoc();

        $io->progressStart(iterator_count($results));

        foreach ($results as $row) {

            // Create new USER
            $user = (new User())
                ->setName($row['name'])
                ->setUsername($row['username'])
                ->setFirstName($row['first_name'])
                ->setPhone($row['phone'])
                ->setEmail($row['email'])
                ->setPassword($row['password'])
                ->setAdministrator($row['administrator'])
                ->setActive($row['active'])
                ->setRoles([$row['roles']]);

            $this->em->persist($user);

            // do a lookup for existing Competitor matching some combination of fields
            $campus = $this->em->getRepository('App:Campus')
                ->findOneBy([
                    'name' => $row['cname'],
                    'active' => $row['cactive']
                ]);
            if ($campus === null) {
            // Or create new CAMPUS
            $campus = (new Campus())
                ->setName($row['cname'])
                ->setActive($row['cactive']);

            $this->em->persist($campus);
            $this->em->flush();
            }

            // Relate the TWO
            $user->setCampus($campus);

            $io->progressAdvance();
        }
        $this->em->flush();

        $io->progressFinish();
        $io->success('CSV Import cleanly');
    }

}