<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    #[Route('/', name: 'home')]
    public function home(ArticleRepository $repo): Response
    {
        // pour récupérer le repository, je le passe en paramètre de la méthode home()
        // cela s'appel une injection de dépendance
        $articles = $repo->findAll();
        // J'utilise la méthode findAll() pour récupérer tous les articles en BDD

        // render() permet de créer une vue en sélectionnant un template et en lui passant de la données
        return $this->render('blog/home.html.twig', [
            'articles' => $articles // J'envoi tout les articles sur la vue

        ]);
    }

    #[Route('/blog/12', name: 'blog_show')]
    public function show(): Response
    {
        return $this->render('blog/show.html.twig');
    }
}
