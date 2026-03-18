<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['name']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('name', 'Nom');

        // Upload image (formulaire)
        yield TextField::new('imageFile', 'Image')
            ->setFormType(VichImageType::class)
            ->setFormTypeOptions([
                'required' => false,        // ✅ Supprime le rouge "obligatoire"
                'allow_delete' => false,    // ✅ Supprime la case "supprimer"
                'download_uri' => false,    // ✅ Supprime le lien/aperçu sous le bouton
                'image_uri' => false,       // ✅ Supprime la prévisualisation de l'image
            ])
            ->onlyOnForms();

        // Aperçu image (liste)
        yield ImageField::new('image', 'Aperçu')
            ->setBasePath('/uploads/categories')
            ->onlyOnIndex();

        // Aperçu image (détail)
        yield ImageField::new('image', 'Image')
            ->setBasePath('/uploads/categories')
            ->onlyOnDetail();
    }
}