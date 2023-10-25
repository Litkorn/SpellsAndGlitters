<?php

namespace App\Controller\Admin;

use Datetime;
use App\Entity\Item;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Creation')
        ->setEntityLabelInPlural('Creations')
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
            TextEditorField::new('description'),
            ImageField::new('picture')->setBasePath('assets/uploads')->setUploadDir('public/assets/uploads')->setUploadedFileNamePattern('[slug]'),
            BooleanField::new('isActive')->onlyOnForms(),
            DateTimeField::new('createdAt')->setFormat('d/M/Y')->hideOnForm(),
            AssociationField::new('category'),
            SlugField::new('slug')->setTargetFieldName('title')->hideOnIndex()
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $item = new $entityFqcn;

        $item->setCreatedAt(new Datetime);
        return $item;
    }

}
