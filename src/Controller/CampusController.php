<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CampusController
 * @package App\Controller
 * @Route(path="campus/", name="campus_")
 */
class CampusController extends AbstractController
{

    /**
     * @Route(path="", name="view")
     */
    public function display() {

    }
    /**
     * @Route(path="modifier/{id}", name="modify")
     */
    public function modify() {

    }
    /**
     * @Route(path="ajouter", name="add")
     */
    public function add() {

    }
    /**
     * @Route(path="supprimer/{id}", name="delete")
     */
    public function delete() {

    }

}