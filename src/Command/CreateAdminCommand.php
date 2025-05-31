<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates a new admin user',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Get admin details interactively
        $email = $io->ask('Enter admin email');
        while (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $io->error('Invalid email format');
            $email = $io->ask('Enter admin email');
        }

        // Check if email already exists
        if ($this->userRepository->findOneBy(['email' => $email])) {
            $io->error('This email is already registered');
            return Command::FAILURE;
        }

        $username = $io->ask('Enter admin username');
        $firstName = $io->ask('Enter admin first name');
        $lastName = $io->ask('Enter admin last name');
        
        $password = $io->askHidden('Enter admin password');
        while (strlen($password) < 6) {
            $io->error('Password must be at least 6 characters long');
            $password = $io->askHidden('Enter admin password');
        }

        $confirmPassword = $io->askHidden('Confirm password');
        if ($password !== $confirmPassword) {
            $io->error('Passwords do not match');
            return Command::FAILURE;
        }

        // Create new admin user
        $admin = new User();
        $admin->setEmail($email);
        $admin->setUsername($username);
        $admin->setFirstName($firstName);
        $admin->setLastName($lastName);
        $admin->setRoles(['ROLE_ADMIN']);

        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword($admin, $password);
        $admin->setPassword($hashedPassword);

        // Save to database
        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success('Admin user has been created successfully');
        $io->table(
            ['Email', 'Username', 'First Name', 'Last Name', 'Role'],
            [[$email, $username, $firstName, $lastName, 'ROLE_ADMIN']]
        );

        return Command::SUCCESS;
    }
} 