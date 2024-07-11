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

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/articles', name: 'admin_articles')]
    public function adminArticles(ArticleRepository $repo, EntityManagerInterface $em): Response
    {
        // Récupération des noms des colonnes SQL
        $colonnes = $em->getClassMetadata(Article::class)->getFieldNames();
        // Récupération de tout les articles
        $articles = $repo->findAll();
        dump($colonnes);
        dump($articles);

        return $this->render('admin/admin_articles.html.twig', [
            'articles' => $articles,
            'colonnes' => $colonnes
        ]);
    }

    #[Route('/admin/article/new', name: 'admin_new_article')]
    #[Route('/admin/{id}/edit-article', name: 'admin_edit_article')]
    public function form(Request $request, EntityManagerInterface $manager, Article $article = null): Response
    {
        // si nous ne récupérons pas d'objet Article, nous en créons un vide et prêt à être rempli
        if (!$article) {
            $article = new Article;
            $article->setCreatedAt(new \DateTime());
        }
        $editMode = $article->getId() !== NULL;
        // La classe Request contient les données véhiculées par les superglobales ($_POST, $_GET)
        $form = $this->createForm(ArticleType::class, $article); // je lie le formulaire à mon objet $article
        // createForm() permet de créer un formulaire d'après le modèle de formulaire (ArticleType)
        dump($request);
        $form->handleRequest($request);
        // handleRequest() permet d'insérer les données du formulaire dans l'objet $article
        // elle permet aussi de faire des vérifications sur le formulaire (quelle méthode ? est-ce que les champs sont remplis ? etc)

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new \DateTime()); // ajout de la date seulement à l'insertion d'un article
            $manager->persist($article); // prépare à linsertion de l'article en BDD
            $manager->flush(); // exécute la requête d'insertion
            $editMode ? $this->addFlash('success', "✅ L'article à été mis à jour avec succès !") : $this->addFlash('success', "✅ L'article à bien été crée !");
            return $this->redirectToRoute('admin_articles');
        }


        return $this->render('blog/form.html.twig', [
            'formArticle' => $form->createView(),
            // createView() renvoie un objet représentant l'affichage du formulaire
            'editMode' => $editMode
        ]);
    }

    #[Route('/admin/{id}/delete-article', name: 'admin_delete_article')]
    public function deleteArticle(Article $article, EntityManagerInterface $manager): Response
    {
        $manager->remove($article);
        $manager->flush();

        $this->addFlash('success', "ℹ️ Article supprimé avec succès !");
        return $this->redirectToRoute('admin_articles');
    }
}
