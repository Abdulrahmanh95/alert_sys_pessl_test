<?php

namespace App\Domain\Service;

use App\Domain\Repository\EmailQueueRepository;
use App\Domain\Repository\UserRepository;
use Helpers\DataParser;
use App\Domain\Repository\StationPayloadRepository;
use Slim\Psr7\UploadedFile;

/**
 * Service.
 */
final class StationPayloadService
{
    /**
     * @var StationPayloadRepository
     */
    private $repository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EmailQueueRepository
     */
    private $emailQueueRepository;

    /**
     * The constructor.
     *
     * @param StationPayloadRepository $repository The primary repository
     * @param EmailQueueRepository $emailQueueRepository
     * @param UserRepository $userRepository
     */
    public function __construct(StationPayloadRepository $repository,
                                UserRepository $userRepository,
                                EmailQueueRepository $emailQueueRepository)
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->emailQueueRepository = $emailQueueRepository;
    }

    /**
     * @param UploadedFile $file
     * @param int $userId
     */
    public function storeAll(UploadedFile $file, int $userId)
    {
        $payloads = file($file->getFilePath(), FILE_IGNORE_NEW_LINES);
        $thresholds = $this->userRepository->getThresholds($userId);

        $payloadData = [];
        $abnormalParams = [];
        foreach ($payloads as $payload) {
            $binaryData = base64_decode($payload);
            $data = DataParser::parseData(substr($binaryData, 14));
            $payloadData[] = $data;

            // Check limits
            if (!array_key_exists('battery', $abnormalParams)) {
                if ($data['battery'] < $thresholds['battery']) {
                    $abnormalParams['battery'] = 'Battery drops below ' . $thresholds['battery'];
                }
            }
            if (!array_key_exists('rh_avg', $abnormalParams)) {
                if ($data['rh_avg'] > $thresholds['rh_avg']) {
                    $abnormalParams['rh_avg'] = 'Relative humidity average exceeded ' . $thresholds['rh_avg'];
                }
            }
            if (!array_key_exists('air_avg', $abnormalParams)) {
                if ($data['air_avg'] > $thresholds['air_avg']) {
                    $abnormalParams['air_avg'] = 'Air temperature average exceeded ' . $thresholds['air_avg'];
                }
            }
        }

        // Exclusion
        $sentAlertTypes = $this->userRepository->getSentAlertsSince2Hours($userId, array_keys($abnormalParams));
        foreach ($sentAlertTypes as $alertType) {
            if (array_key_exists($alertType, $abnormalParams)) {
                unset($abnormalParams[$alertType]);
            }
        }

        $this->emailQueueRepository->sendAlertEmail($this->userRepository->getUserById($userId), $abnormalParams);

        $this->repository->storeAll($payloadData);
    }
}