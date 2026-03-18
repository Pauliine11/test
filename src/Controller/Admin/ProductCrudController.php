<?php
namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

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

        yield AssociationField::new('category', 'Catégorie')
            ->setCrudController(CategoryCrudController::class)
            ->setRequired(false);

        // ✅ Éditeur rich text sur les formulaires
        yield TextareaField::new('description', 'Description')
            ->onlyOnForms();

        // ✅ Texte brut sur la liste et le détail
        yield TextField::new('description', 'Description')
            ->onlyOnIndex();

        yield TextField::new('description', 'Description')
            ->onlyOnDetail();

        yield MoneyField::new('price', 'Prix')
            ->setCurrency('EUR')
            ->setStoredAsCents();

        // Upload image (formulaire)
        yield TextField::new('imageFile', 'Image')
            ->setFormType(VichImageType::class)
            ->setFormTypeOptions([
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
            ])
            ->onlyOnForms();

        // Aperçu image (liste)
        yield ImageField::new('image', 'Aperçu')
            ->setBasePath('/uploads/products')
            ->onlyOnIndex();

        // Aperçu image (détail)
        yield ImageField::new('image', 'Image')
            ->setBasePath('/uploads/products')
            ->onlyOnDetail();

        yield DateTimeField::new('updatedAt', 'Modifié le')->onlyOnDetail();
    }
}