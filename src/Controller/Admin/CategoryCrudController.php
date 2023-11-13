<?php

namespace App\Controller\Admin;

use Datetime;
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Categorie')
        ->setEntityLabelInPlural('Categories')
        ->setDefaultSort(['id' => 'DESC'])
        ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            DateTimeField::new('createdAt')->setFormat('d/M/Y à H:m:s')->hideOnForm(),
            CollectionField::new('items')->setTemplatePath('/admin/fields/items.html.twig')->onlyOnIndex(),
            SlugField::new('slug')->setTargetFieldName('title')->hideOnIndex()
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $category = new $entityFqcn;
        $category->setCreatedAt(new Datetime);
        return $category;
    }
}
