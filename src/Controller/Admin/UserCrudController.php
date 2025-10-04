<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('username', 'Pseudo');
        yield EmailField::new('email', 'Email');
        yield ChoiceField::new('roles', 'Rôles')
            ->setChoices([
                'Admin' => 'ROLE_ADMIN',
                'Modérateur' => 'ROLE_MODERATOR',
                'Utilisateur' => 'ROLE_USER',
            ])
            ->allowMultipleChoices();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // Seul ADMIN peut supprimer
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            // Seul ADMIN peut créer de nouveaux users
            ->setPermission(Action::NEW, 'ROLE_ADMIN');
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $qb = parent::createIndexQueryBuilder(
            $searchDto,
            $entityDto,
            $fields,
            $filters
        );

        $user = $this->getUser();

        // Si modérateur, ne montre que ses propres salons
        if ($this->isGranted('ROLE_MODERATOR') && !$this->isGranted('ROLE_ADMIN')) {
            $qb->andWhere('entity.owner = :user')
                ->setParameter('user', $user);
        }

        return $qb;
    }
}
