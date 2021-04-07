<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @Route(path="profil/", name="user_")
 */
class UserController extends AbstractController
{

    /**
     * @Route(path="modifier/{id}", name="modify")
     */
    public function modify() {

    }
    /**
     * @Route(path="{id}", name="profil")
     */
    public function display() {

    }
    /**
     * @Route(path="connexion", name="connection")
     */
    public function connection() {

    }


}