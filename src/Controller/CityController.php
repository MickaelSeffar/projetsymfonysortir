<?php



namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CityController
 * @package App\Controller
 * @Route(path="ville/", name="city_")
 */
class CityController
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