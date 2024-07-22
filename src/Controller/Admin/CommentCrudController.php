<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextareaField::new('content', 'Content')->renderAsHtml()->onlyOnIndex(),
            TextEditorField::new('content', 'Content')->onlyOnForms(),
            DateTimeField::new('createdAt', 'Created At')->setFormat('d/M/Y')->hideOnForm(),
            AssociationField::new('article', 'Article Title'),
            TextField::new('author', 'Author'),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $comment = new Comment;
        $comment->setCreatedAt(new \DateTime());
        return $comment;
    }
}
