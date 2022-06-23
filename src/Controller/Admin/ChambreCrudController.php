<?php

namespace App\Controller\Admin;

use App\Entity\Chambre;
use Symfony\Config\VichUploaderConfig;
use Symfony\Component\Form\FormTypeInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ChambreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Chambre::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Chambres')
            ->setEntityLabelInSingular('Chambre')
            ->setDefaultSort(['id' => 'asc']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('titre', 'Titre');
        yield TextareaField::new('description', 'Déscription')->hideOnIndex();
        yield TextField::new('description_court', 'Court Déscription');
        yield TextField::new('file', 'Image')->setFormType(VichImageType::class)->onlyOnForms();
        yield ImageField::new('image' , 'Image')->setBasePath('/img/room/')->onlyOnIndex();
        yield AssociationField::new('categorie', 'Catégorie')->setCrudController(CategoryCrudController::class);
        yield MoneyField::new('prix' , 'Prix')->setCurrency('EUR');
        yield DateTimeField::new('enregistre_at', 'Date')->setFormat('d/M/Y HH:mm aaa')->hideOnForm();
        //yield DateTimeField::new('enregistre_at', 'Date')->setFormTypeOptions(['data' => new \DateTime('now')])->setFormat('d/M/Y HH:mm');
    }

    public function createEntity(string $entityFqcn)
    {
        $chambre = new Chambre();
        $chambre->setEnregistreAt(new \DateTime('now'));
        return $chambre;
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
