<?php
// …
use Symfony\Component\Routing\Attribute\Route;

class SomeController extends AbstractController
{
    //[Route(‘/some-page’, name: ‘app_some_page’)]
    public function somePage(): Response
{
// …


/* SomeController
Premièrement, vos classes de controllers doivent se trouver dans le dossier  src/Controller  et avoir un nom qui se termine par  Controller  . Par exemple,  src/Controller/SomeController.php  contenant la classe  App\Controller\SomeController  .
Deuxièmement, vos classes de controllers doivent étendre la classe  Symfony\Bundle\FrameworkBundle\Controller\AbstractController  . Cette classe vous donne accès à tout un tas de raccourcis très utiles, ainsi qu’à des fonctionnalités qui vous faciliteront grandement la vie.
Enfin, si vous voulez vraiment avoir une application claire, facile à maintenir et à garder organisée, il est très fortement conseillé de garder vos classes de controllers les plus courtes possibles. Ne mettez dedans qu’un nombre limité de routes avec leurs controllers, et ne mettez que le strict minimum de logique dans ces controllers.
 */