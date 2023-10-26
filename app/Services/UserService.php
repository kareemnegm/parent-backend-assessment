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
     */
    public function listUsers(array $request): array
    {
        try {
            $jsonFiles = load_json('json_files');
            $filteredData = [];

            // Filters
            //check if name is set
            $name = $request['name'] ?? null;
            $currency = $request['currency'] ?? null;
            $status = $request['status'] ?? $request['statusCode'] ?? null;
            $statusCodes = $this->getStatusCodeValue($status) ?? null;
            $balanceMin = $request['balanceMin'] ?? null;
            $balanceMax = $request['balanceMax'] ?? null;
            // Filter the data
            foreach ($jsonFiles as $key => $data) {
                $isMatch = (!$name || $key === $name) &&
                    (!$currency || (!isset($data->currency) || $data->currency === $currency)) &&
                    (!$statusCodes || (
                        (isset($data->statusCode) && in_array($data->statusCode, $statusCodes)) ||
                        (isset($data->status) && in_array($data->status, $statusCodes))
                    ));

                if ($isMatch) {
                    if ($balanceMin === null && $balanceMax === null) {
                        $filteredData[] = $data; // No balance filter, all other criteria met
                    } else if (
                        (
                            (isset($data->balance) && $data->balance >= $balanceMin && $data->balance <= $balanceMax) ||
                            (isset($data->parentAmount) && $data->parentAmount >= $balanceMin && $data->parentAmount <= $balanceMax)
                        )
                    ) {
                        $filteredData[] = $data; // Meets balance criteria
                    }
                }
            }

            return $this->success(200, ['users' => $filteredData]);
        } catch (Exception $exception) {
            return $this->failedWithException($exception);
        }
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
