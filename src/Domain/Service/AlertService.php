<?php

namespace App\Domain\Service;

use App\Domain\Repository\AlertRepository;
use App\Domain\Repository\UserRepository;
use App\Exception\ValidationException;

/**
 * Service.
 */
final class AlertService
{
    /**
     * @var AlertRepository
     */
    private $repository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * The constructor.
     *
     * @param AlertRepository $repository The repository
     * @param UserRepository $userRepository
     */
    public function __construct(AlertRepository $repository,
                                UserRepository $userRepository)
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new alert.
     *
     * @param array $data The form data
     *
     * @return int The new alert ID
     */
    public function createAlert(array $data): int
    {
        // Input validation
        $this->validateNewAlert($data);

        $data['user_id'] = $this->userRepository->getUserIdByEmail($data['user_email']);

        // Insert alert
        return $this->repository->insertAlerts($data);
    }

    /**
     * Input validation.
     *
     * @param array $data The form data
     *
     * @throws ValidationException
     *
     * @return void
     */
    private function validateNewAlert(array $data): void
    {
        $errors = [];

        $count = $this->userRepository->countUserByEmail($data['user_email']);

        if ($count <= 0) {
            $errors['user_email'] = 'Email not found!';
        }

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
}