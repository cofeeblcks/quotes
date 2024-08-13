<?php

namespace App\Traits;

trait WithRecordPerPage
{
    /**
     * Get avalible records per page
     */
    public function getAvailableRecordsPerPage(): array
    {
        return [
            [
                'id' => 10,
                'name' => '10 Registros',
            ],
            [
                'id' => 25,
                'name' => '25 Registros',
            ],
            [
                'id' => 50,
                'name' => '50 Registros',
            ],
            [
                'id' => 100,
                'name' => '100 Registros',
            ]
        ];
    }
}
