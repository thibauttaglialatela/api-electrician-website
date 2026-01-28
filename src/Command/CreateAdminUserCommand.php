<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'CreateAdminUser',
    description: 'Create an admin user, hash his password and register in the db',
)]
class CreateAdminUserCommand
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(
        #[Argument('Admin username')] string $adminUsername,
        #[Argument('Admin password')] string $plainPassword,
        OutputInterface $output,
    ): int {
        $adminUser = new User();
        $adminUser->setUsername($adminUsername);

        $hashedPassword = $this->passwordHasher->hashPassword($adminUser, $plainPassword);
        $adminUser->setPassword($hashedPassword);

        $adminUser->setRoles(['ROLE_ADMIN']);

        $this->em->persist($adminUser);
        $this->em->flush();

        $output->writeln('Un nouvel administrateur a été créé.');

        return Command::SUCCESS;

    }
}
