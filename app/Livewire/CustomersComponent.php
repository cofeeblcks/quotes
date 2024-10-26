<?php

namespace App\Livewire;

use App\Actions\Customers\CreateCustomer;
use App\Actions\Customers\DeleteCustomer;
use App\Actions\Customers\CustomerFinder;
use App\Actions\Customers\CustomersList;
use App\Actions\Customers\UpdateCustomer;
use App\Enums\OutputList;
use App\Traits\WithRecordPerPage;
use App\Traits\WithSelectOne;
use App\Traits\WithToastNotifications;
use Livewire\Component;
use Livewire\WithPagination;

class CustomersComponent extends Component
{
    use WithPagination;
    use WithToastNotifications;
    use WithRecordPerPage, WithSelectOne;

    public int $recordsPerPage = 10;
    public ?string $filterSearch;

    public bool $showModalDeleteCustomer = false;
    public bool $showModalCreateCustomer = false;
    public bool $createCustomer= true;
    public bool $inputDisabled = false;

    public string $customerName;
    public int $customerId;

    public array $customerData = [
        'name' => null,
        'email' => null,
        'phone' => null,
        'address' => null,
    ];

    public function render()
    {
        return view('livewire.customers-component', [
            'customers' => $this->getCustomers()
        ]);
    }

    public function updated($field, $value): void
    {
        if (in_array($field, array_keys($this->queryString))) {
            $this->resetPage('customers-component_page');
        }
    }

    protected array $queryString = [
        'filterSearch' => ['as' => 'Busqueda', 'except' => ''],
        'recordsPerPage' => ['as' => 'Registros', 'except' => 10],
    ];

    private function resetInputs()
    {
        $this->reset([
            'customerId',
            'inputDisabled',
            'customerData',
        ]);
        $this->resetValidation();
    }

    public function getCustomers()
    {
        $response = (new CustomersList())
            ->setParam('output', OutputList::PAGINATE)
            ->setParam('recordsPerPage', $this->recordsPerPage)
            ->execute(
                filter: $this->filterSearch ?? null,
            );

        if (!$response['success']) {
            return [];
        }
        return $response['customers'];
    }

    public function validationAttributes()
    {
        return [
            'customerData.name' => 'nombre del cliente',
            'customerData.email' => 'correo electrónico'
        ];
    }

    public function validateCustomerData()
    {
        $this->validate([
            'customerData.name' => ['required', 'string'],
            'customerData.email' => ['required', 'email', $this->createCustomer ? 'unique:customers,email' : 'exists:customers,email'],
        ]);

        $this->resetErrorBag();
    }

    /**
     * Metodo para registrar usuario desde el compoenente usuario
     * @author Hadik Chavez (ChivoDev) -  CofeeBlcks <cofeeblcks@gmail.com, chavezhadik@gmail.com>
     * @return void
     */
    public function store()
    {
        try {
            $this->validateCustomerData();
        } catch (\Throwable $th) {
            throw $th;
        }

        $response = (new CreateCustomer())->execute($this->customerData);

        if (!$response['success']) {
            $this->showError('Error guardando datos', "Ha ocurrido un error guardando los datos del usuario.", 10000);
            return;
        }

        $this->showModalCreateCustomer = !$this->showModalCreateCustomer;
        $this->showSuccess('Datos guardados', $response['message'], 5000);
    }

    /**
     * Metodo para actualizar los datos del proyecto, siendo este actualizable cuando se emite observación
     * @author Hadik Chavez (ChivoDev) -  CofeeBlcks <cofeeblcks@gmail.com, chavezhadik@gmail.com>
     * @return void
     */
    public function update()
    {
        try {
            $this->validateCustomerData();
        } catch (\Throwable $th) {
            throw $th;
        }

        $response = (new UpdateCustomer())->execute(
            $this->customerId,
            $this->customerData
        );

		if (!$response['success']) {
            $this->showError('Error guardando datos', "Ha ocurrido un error guardando los datos del usuario.", 10000);
            exit;
        }

        $this->showModalCreateCustomer = !$this->showModalCreateCustomer;
        $this->showSuccess('Datos guardados', $response['message'], 5000);
    }

    /**
     * Eliminar area
     * @author Hadik Chavez (ChivoDev) -  CofeeBlcks <cofeeblcks@gmail.com, chavezhadik@gmail.com>
     * @return void
     */
    public function destroy(): void
    {
        $response = (new DeleteCustomer())->execute($this->customerId);

        if( !$response['success'] ){
            $this->showDanger('Error eliminando cotización', "Ha ocurrido un error eliminando el cotización. " . $response['message'], 10000);
        }
        $this->showModalDeleteCustomer = false;
        $this->resetInputs();
        $this->showSuccess('Cotización eliminada', 'Se ha eliminado la cotización exitosamente.', 5000);
    }

    // Metodos para abrir modales
    public function create(): void
    {
        $this->resetInputs();
        $this->createCustomer = true;
        $this->inputDisabled = false;
        $this->showModalCreateCustomer = !$this->showModalCreateCustomer;
    }

    /**
     * Consultar información del usuario para visualizar en la modal y/o editar
     * @author Hadik Chavez (ChivoDev) -  CofeeBlcks <cofeeblcks@gmail.com, chavezhadik@gmail.com>
     * @param integer $customerId
     * @return void
     */
    public function edit(int $customerId)
    {
        $this->resetInputs();

        $customer = (new CustomerFinder())->execute(customerId: $customerId);

        $this->customerData = [
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'address' => $customer->address,
        ];

        $this->customerId = $customerId;

        $this->createCustomer = false;
        $this->inputDisabled = true;
        $this->showModalCreateCustomer = !$this->showModalCreateCustomer;
    }

    public function delete(int $customerId): void
    {
        $customer = (new CustomerFinder())->execute(customerId: $customerId);

        $this->customerName = $customer->name;
        $this->customerId = $customerId;
        $this->showModalDeleteCustomer = true;
    }
}
