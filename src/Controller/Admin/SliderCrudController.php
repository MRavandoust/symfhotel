<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SliderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Slider::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Slides')
            ->setEntityLabelInSingular('Slide')
            ->setDefaultSort(['id' => 'asc']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre' , 'Titre'),
            TextField::new('imgFile' , 'Image')->setFormType(VichImageType::class)->onlyOnForms(),
            ImageField::new('image' , 'Image')->setBasePath('/img/slider/')->hideOnForm(),
            IntegerField::new('ordre' , 'Ordre dans slider')
        ];
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
