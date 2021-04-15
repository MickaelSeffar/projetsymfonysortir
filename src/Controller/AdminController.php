<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\AdminType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @Route(path="admin/", name="admin_")
 */
class AdminController extends AbstractController
{

    /**
     * @Route(path="csv", name="csv")
     */
    public function addUserCsv(Request $request, EntityManagerInterface $entityManager)
    {
        $user = new User();
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $csvFieldAdmin */
            $csvFieldAdmin = $form->get('csvFieldAdmin')->getData();

            if ($csvFieldAdmin) {
                $originalFilename = pathinfo($csvFieldAdmin->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = 'DATA.csv';

                try {
                    $csvFieldAdmin->move(
                        $this->getParameter('csv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $user->setCsvFieldAdmin($newFilename);
            }
            $this->addFlash('success', 'Le fichier CSV a bien été envoyé');
            return $this->redirectToRoute('home_welcome');
        }
        return $this->render('admin/addusercsv.html.twig', ['formAdmin' => $form->createView()]);
    }

}