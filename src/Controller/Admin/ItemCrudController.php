<?php

namespace App\Controller\Admin;

use Datetime;
use App\Entity\Item;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Item::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextEditorField::new('description'),
            DateTimeField::new('createdAt')->setFormat('d/M/Y à H:m:s')->hideOnForm(),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $comment = new $entityFqcn;
        $comment->setCreatedAt(new Datetime);
        return $comment;
    }
}
