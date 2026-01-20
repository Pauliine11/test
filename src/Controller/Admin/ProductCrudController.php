<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['name']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        yield TextField::new('name', 'Nom');

        // Prix stocké en centimes dans la DB
        yield MoneyField::new('price', 'Prix')
            ->setCurrency('EUR')
            ->setStoredAsCents();

        // Upload image (form)
        yield TextField::new('imageFile', 'Image')
            ->setFormType(VichImageType::class)
            ->onlyOnForms();

        // Preview image (liste)
        yield ImageField::new('image', 'Aperçu')
            ->setBasePath('/uploads/products')
            ->onlyOnIndex();

        // Preview image (détail)
        yield ImageField::new('image', 'Image')
            ->setBasePath('/uploads/products')
            ->onlyOnDetail();

        // Optionnel : voir le updatedAt en détail
        yield DateTimeField::new('updatedAt', 'Modifié le')->onlyOnDetail();
    }
}
