<?php

namespace App\Controller\Admin;

use App\Entity\Settlement;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\Attribute\Required;

class SettlementCrudController extends AbstractCrudController
{
    protected ?string $coatOfArmsDirectory = null;

    #[Required]
    public function loadDependencies(#[Autowire(param: 'coat_of_arms_directory')] string $coatOfArmsDirectory): void
    {
        $this->coatOfArmsDirectory = $coatOfArmsDirectory;
    }

    public static function getEntityFqcn(): string
    {
        return Settlement::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->showEntityActionsInlined();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield TextField::new('mayorName');
        yield TextField::new('cityHallAddress');
        yield TextField::new('phone');
        yield TextField::new('email');
        yield AssociationField::new('parent');
        yield AssociationField::new('childSettlements');
        yield UrlField::new('coatOfArmsPathRemote')->hideOnIndex();
        yield ImageField::new('coatOfArmsPath')
            ->setUploadDir($this->coatOfArmsDirectory)
            ->setBasePath('uploads/coat-of-arms')
            ->setUploadedFileNamePattern('[slug].[extension]')
            ->setLabel('Coat of Arms Image')
            ->hideOnIndex()
        ;
        yield TextField::new('webAddress');
    }
}
