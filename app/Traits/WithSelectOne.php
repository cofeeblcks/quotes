<?php

namespace App\Traits;

use App\Enums\OutputList;

trait WithSelectOne
{
    public array $filtersSelectOne = [];

    /**
     * Get grouped data
     * @param string $id
     * @param mixed $dataset
     * @param string|null $groupBy
     * @param string $filter
     * @param array $params
     */
    public function getGroupedDataSelectOne(
        mixed $dataset,
        ?string $groupBy = null,
        string $filter = '',
        string $columnName = 'name',
        array $params = [],
    ): array
    {
        // Obtener los datos
        $response = match (gettype($dataset)) {
            'string' => $this->loadDataSelectOne($dataset, $params, $filter),
            'array' => collect($dataset)->filter(function ($item) use ($filter,$columnName) {
                return str_contains(strtolower($item[$columnName]), strtolower($filter)) || empty($filter);
            })->values()->toArray(),
            'object' => $dataset->filter(function ($item) use ($filter,$columnName) {
                return str_contains(strtolower($item[$columnName]), strtolower($filter)) || empty($filter);
            })->values()->toArray(),
        };
        $data = key_exists('data', $response) ? $response['data'] : $response;

        // Agrupar los datos
        $groupedData = is_null($groupBy) ? [''=>$data] : collect($data)->groupBy($groupBy)->toArray();

        return $groupedData;
    }

    /**
     * Get selected items
     * @param string $id
     * @param mixed $dataset
     * @param string|null $columnId
     */
    public function getSelectedItemsSelectOne(
        string $id,
        mixed $dataset,
        string $columnId = 'id',
        string $columnName = 'name',
        ?string $groupBy = null,
        array $params = [],
    ): array
    {
        // Obtener ids de los items seleccionados
        $levels = explode('.', $id);
        $ids = [];
        if (property_exists($this, reset($levels))) {
            $ids = $this->{reset($levels)};
            // Si hay mas de un nivel ir recorriendo los niveles
            if (count($levels) > 1) {
                foreach (array_slice($levels, 1) as $level) {
                    $ids = $ids[$level];
                }
            }
        }
        $ids = is_array($ids)
            ? $ids
            : (empty($ids) ? [] : [$ids]);

        // Obtener los items seleccionados
        $data = match (gettype($dataset)) {
            'string' => $this->loadSelectedItemsSelectOne($dataset, $ids, $params),
            'array' => collect($dataset)->filter(function ($item) use ($ids, $columnId) {
                return in_array($item[$columnId], $ids, true);
            })->values()->toArray(),
            'object' => $dataset->filter(function ($item) use ($ids, $columnId) {
                return in_array($item[$columnId], $ids, true);
            })->values()->toArray(),
        };

        // Añadir los items que son string
        foreach ($ids as $id) {
            if (is_string($id)) {
                $data[] = [
                    $columnId => $id,
                    $columnName => $id,
                    $groupBy??'group-by' => ''
                ];
            }
        }

        return $data;
    }

    /**
     * Load data from a list action
     * @param string $actionListPath
     * @param array $params
     * @param string $textFilter
     */
    private function loadDataSelectOne($actionListPath, $params, $textFilter = ''): array
    {
        // Instanciar la clase de listado
        $actionList = new $actionListPath();

        // Verificar si se han enviado filtros y aplicarlos
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                // Castear el valor del parametro
                $value = match ($param) {
                    'output' => OutputList::from($value),
                    default => $value,
                };
                $actionList->$param = $value;
            }
        }
        // Ejecutar el metodo de la clase
        $response = $actionList->execute($textFilter);

        $result = array_values($response)[1];
        if (is_string($result)) {
            if (env('APP_DEBUG') === true) {
                throw new \Exception($result);
            }
            return [];
        }

        return array_values($response)[1]->toArray();
    }

    /**
     * Load items selected from a list action
     * @param string $actionListPath
     * @param mixed $ids
     */
    private function loadSelectedItemsSelectOne($actionListPath, $ids, $params): array
    {
        $actionList = new $actionListPath();
        // Verificar si se han enviado filtros y aplicarlos
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                if ($param === 'output') {
                    continue;
                }
                $actionList->$param = $value;
            }
        }
        $actionList->idsFilter = is_array($ids) ? $ids : [$ids];
        $response = $actionList->execute();
        if (!$response['success']) {
            throw new \Exception($response['message']);
        }
        return array_values($response)[1]->toArray();
    }
}
