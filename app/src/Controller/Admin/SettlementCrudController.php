<?php

namespace App\Controller\Admin;

use App\Entity\Settlement;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SettlementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Settlement::class;
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
        yield ImageField::new('coatOfArmsPath')
            ->setUploadDir('public/uploads/images')
            ->setBasePath('uploads/images')
            ->setLabel('Coat of Arms Image');
        yield TextField::new('webAddress');
    }
}
