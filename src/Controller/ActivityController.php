<?php



namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ActivityController
 * @package App\Controller
 * @Route(path="activitee/", name="activity_")
 */
class ActivityController extends AbstractController
{

    /**
     * @Route(path="ajouter", name="add")
     */
    public function add() {

    }
    /**
     * @Route(path="{id}", name="view")
     */
    public function display() {

    }
    /**
     * @Route(path="modifier/{id}", name="modify")
     */
    public function modify() {

    }
    /**
     * @Route(path="annuler/{id}", name="cancel")
     */
    public function cancel() {

    }
}