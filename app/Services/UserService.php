<?php

namespace App\Services;

use App\Http\Traits\ResponseTrait;
use Exception;

class UserService
{
    use ResponseTrait;

    /**
     * List Users from DataProvider
     *
     * @param array $request
     * @return array<string, mixed>
     */public function listUsers(array $request): array
{
    try {
        $jsonFiles = load_json('json_files');
        $filteredData = [];

        // Define filters
        $filters = [
            'name' => $request['name'] ?? null,
            'currency' => $request['currency'] ?? null,
            'statusCodes' => $this->getStatusCodeValue($request['status'] ?? $request['statusCode'] ?? null),
            'balanceMin' => $request['balanceMin'] ?? null,
            'balanceMax' => $request['balanceMax'] ?? null,
        ];

        // Filter the data
        foreach ($jsonFiles  as $key => $data) {
            if ($this->dataMatchesFilters($data, $filters,$key)) {
                $filteredData[] = $data;
            }
        }

        return $this->success(200,$filteredData);
    } catch (Exception $exception) {
        return $this->failedWithException($exception);
    }
}

// Helper function to check if data matches all filters
private function dataMatchesFilters($data, $filters,$key): bool
{
    if ($filters['name'] !== null && $key !== $filters['name']) {
        return false;
    }

    if ($filters['currency'] !== null && (!isset($data->currency) || $data->currency !== $filters['currency'])) {
        return false;
    }

    if ($filters['statusCodes']) {
        if (!isset($data->statusCode) && !isset($data->status)) {
            return false;
        }
        $status = $data->statusCode ?? $data->status;
        if (!in_array($status, $filters['statusCodes'])) {
            return false;
        }
    }

    if (
        ($filters['balanceMin'] !== null || $filters['balanceMax'] !== null) &&
        (
            (!isset($data->balance) && !isset($data->parentAmount)) ||
            (isset($data->balance) && ($data->balance < $filters['balanceMin'] || $data->balance > $filters['balanceMax'])) ||
            (isset($data->parentAmount) && ($data->parentAmount < $filters['balanceMin'] || $data->parentAmount > $filters['balanceMax']))
        )
    ) {
        return false;
    }

    return true;
}



    /**
     * Retrieves the value associated with a given status code.
     *
     * @param string $status The status code to retrieve the value for.
     * @return array|null The value associated with the status code, or null if the status code is not found.
     */
    private function getStatusCodeValue($status)
    {
        $statusMap = [
            'authorised' => [1, 100],
            'decline' => [2, 200],
            'refunded' => [3, 300],
        ];

        return $statusMap[$status] ?? null;
    }
}
