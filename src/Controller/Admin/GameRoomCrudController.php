<?php

namespace App\Controller\Admin;

use App\Entity\GameRoom;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GameRoomCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GameRoom::class;
    }
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom du salon');
        yield AssociationField::new('owner', 'Capitaine');
        yield AssociationField::new('players', 'Joueurs')
            ->setFormTypeOption('by_reference', false);
    }
}
