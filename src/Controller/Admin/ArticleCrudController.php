<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $defaut = $_SERVER['DOCUMENT_ROOT'] . '\images\defaut\defaut.jpg';
        $path = $_SERVER['DOCUMENT_ROOT'] . '\images\articles\defaut.jpg';
        copy($defaut, $path);

        return [
            TextField::new('title', 'Title'),
            ImageField::new('image')->setBasePath('images/articles')->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')->setUploadDir('public\images\articles')->setRequired(false),
            TextareaField::new('content', 'Content'),
            DateTimeField::new('createdAt', 'Created At')->setFormat('d/M/Y')->hideOnForm(),
            AssociationField::new('category', 'Category'),
            DateTimeField::new('updatedAt', 'Updated At')->setFormat('d/M/Y')->hideOnForm(),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        // createEntity() est exécutée lorsque je clique sur add article
        // elle permet d'exécuter du code avant d'afficher la page du formulaire de création
        // ici, je défini une date de création et de mise à jour, et une image par défaut
        $article = new Article;
        $article->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->setImage('defaut.jpg');
        return $article;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // updateEntity() est exécutée lors de la soumission du formulaire de mise à jour
        $isFile = $entityInstance->getImage();

        if (!$isFile) {
            // cette image doit être placé dans le dossier des images articles
            $entityInstance->setImage('defaut.jpg');
        }

        $entityInstance->setUpdatedAt(new \DateTime());
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
