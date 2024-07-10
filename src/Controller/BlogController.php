<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        dump($articles);
        // J'utilise la méthode findAll() pour récupérer tous les articles en BDD

        // render() permet de créer une vue en sélectionnant un template et en lui passant de la données
        return $this->render('blog/home.html.twig', [
            'articles' => $articles // J'envoi tout les articles sur la vue

        ]);
    }

    #[Route('/blog/show/{id}', name: 'blog_show')]
    public function show(Article $article): Response
    {
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }

    #[Route('/blog/new', name: 'blog_create')]
    #[Route('/blog/edit/{id}', name: 'blog_edit')]
    public function form(Request $request, EntityManagerInterface $manager, Article $article = null): Response
    {
        // si nous ne récupérons pas d'objet Article, nous en créons un vide et prêt à être rempli
        if(!$article) {
            $article = new Article;
            $article->setCreatedAt(new \DateTime());
        }
        // La classe Request contient les données véhiculées par les superglobales ($_POST, $_GET)
        $form = $this->createForm(ArticleType::class, $article); // je lie le formulaire à mon objet $article
        // createForm() permet de créer un formulaire d'après le modèle de formulaire (ArticleType)
        dump($request);
        $form->handleRequest($request);
        // handleRequest() permet d'insérer les données du formulaire dans l'objet $article
        // elle permet aussi de faire des vérifications sur le formulaire (quelle méthode ? est-ce que les champs sont remplis ? etc)

        if($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new \DateTime()); // ajout de la date seulement à l'insertion d'un article
            $manager->persist($article); // prépare à linsertion de l'article en BDD
            $manager->flush(); // exécute la requête d'insertion
            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);
        }


        return $this->render('blog/form.html.twig', [
            'formArticle' => $form->createView()
            // createView() renvoie un objet représentant l'affichage du formulaire
        ]);
    }


}
