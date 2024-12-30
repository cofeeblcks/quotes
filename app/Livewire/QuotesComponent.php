<?php

namespace App\Livewire;

use App\Actions\Quotes\CreateQuote;
use App\Actions\Quotes\DeleteQuote;
use App\Actions\Quotes\QuoteFinder;
use App\Actions\Quotes\QuotesList;
use App\Actions\Quotes\UpdateQuote;
use App\Enums\OutputList;
use App\Traits\NotificationQuote;
use App\Traits\WithRecordPerPage;
use App\Traits\WithSelectOne;
use App\Traits\WithToastNotifications;
use Livewire\Component;
use Livewire\WithPagination;

class QuotesComponent extends Component
{
    use WithPagination;
    use WithToastNotifications, NotificationQuote;
    use WithRecordPerPage, WithSelectOne;

    public int $recordsPerPage = 10;
    public ?string $filterSearch;

    public bool $showModalViewPdf = false;

    public bool $showModalDeleteQuote = false;
    public bool $showModalCreateQuote = false;
    public bool $createQuote= true;
    public bool $inputDisabled = false;
    public bool $readOnly = false;

    public int $quoteId;
    public int $quoteConsecutive;

    public bool $newCustomer = false;

    public array $quoteData = [
        'description' => null,
        'dateQuote' => null,
        'customerId' => null,
        'withTotal' => false,
    ];

    public array $quoteDetailsData = [
        [
            'description' => null,
            'quantity' => null,
            'unitCost' => null,
        ]
    ];

    public array $customerData = [
        'name' => null,
        'email' => null,
        'phone' => null,
        'address' => null,
    ];

    public function render()
    {
        return view('livewire.quotes-component', [
            'quotes' => $this->getQuotes()
        ]);
    }

    public function updated($field, $value): void
    {
        if (in_array($field, array_keys($this->queryString))) {
            $this->resetPage('quotes-component_page');
        }
    }

    public function updatedQuoteDataCustomerId($value)
    {
        if( $value && is_string($value) ){
            $this->newCustomer = true;
            $this->customerData['name'] = $this->quoteData['customerId'];
        }else{
            $this->newCustomer = false;
        }
    }

    public function cancelRegisterCustomerAdd()
    {
        $this->newCustomer = false;
        $this->quoteData['customerId'] = null;
        $this->customerData['name'] = null;
    }

    protected array $queryString = [
        'filterSearch' => ['as' => 'Busqueda', 'except' => ''],
        'recordsPerPage' => ['as' => 'Registros', 'except' => 10],
    ];

    private function resetInputs()
    {
        $this->reset([
            'quoteId',
            'inputDisabled',
            'readOnly',
            'newCustomer',
            'quoteData',
            'quoteDetailsData',
            'customerData',
        ]);
        $this->resetValidation();
    }

    public function getQuotes()
    {
        $response = (new QuotesList())
            ->setParam('output', OutputList::PAGINATE)
            ->setParam('recordsPerPage', $this->recordsPerPage)
            ->execute(
                filter: $this->filterSearch ?? null,
            );

        if (!$response['success']) {
            return [];
        }
        return $response['quotes'];
    }

    public function validationAttributes()
    {
        return [
            'quoteData.description' => 'descripción',
            'quoteData.dateQuote' => 'fecha de cotización',
            'quoteData.customerId' => 'cliente',
            'quoteData.withTotal' => 'totalizado',
            'quoteDetailsData.*' => 'detalle de cotizacion',
            'customerData.name' => 'nombre del cliente',
            'customerData.email' => 'correo electrónico'
        ];
    }

    private function validateStore()
    {
        $this->validate([
            'quoteData.description' => ['nullable', 'string'],
            'quoteData.dateQuote' => ['required', 'date'],
            'quoteData.customerId' => $this->newCustomer ? ['required', 'string'] : ['required', 'numeric', 'exists:customers,id'],
            'quoteData.withTotal' => ['required', 'boolean'],
            'quoteDetailsData' => ['required', 'array', 'min:1'],
            'quoteDetailsData.*.description' => ['required', 'string'],
            'quoteDetailsData.*.quantity' => ['required', 'numeric'],
            'quoteDetailsData.*.unitCost' => ['required', 'string'],
        ]);

        $this->resetErrorBag();
    }

    public function validateCustomerData()
    {
        $this->validate([
            'customerData.name' => ['required', 'string'],
            'customerData.email' => ['required', 'email', 'unique:customers,email'],
        ]);

        $this->resetErrorBag();
    }

    public function addRow()
    {
        $this->quoteDetailsData[] = array_fill_keys(['description', 'quantity', 'unitCost'], null);;
    }

    public function removeRow(int $index): void
    {
        unset($this->quoteDetailsData[$index]);

        $this->quoteDetailsData = array_values($this->quoteDetailsData);
    }

    /**
     * Metodo para registrar usuario desde el compoenente usuario
     * @author Hadik Chavez (ChivoDev) -  CofeeBlcks <cofeeblcks@gmail.com, chavezhadik@gmail.com>
     * @return void
     */
    public function store()
    {
        try {
            $this->validateStore();
            if( $this->newCustomer ){
                $this->validateCustomerData();
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        $response = (new CreateQuote())->execute(
            array_merge(
                [
                    'quote' => $this->quoteData,
                    'details' => $this->quoteDetailsData,
                    'customer' => $this->customerData
                ]
            )
        );

        if (!$response['success']) {
            $this->showError('Error guardando datos', "Ha ocurrido un error guardando los datos del usuario.", 10000);
            return;
        }

        // $this->notificationEmailQuote($response['quote']);

        $this->showModalCreateQuote = !$this->showModalCreateQuote;
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
            $this->validateStore();
        } catch (\Throwable $th) {
            throw $th;
        }

        $response = (new UpdateQuote())->execute(
            $this->quoteId,
            array_merge(
                [
                    'quote' => $this->quoteData,
                    'details' => $this->quoteDetailsData,
                ]
            )
        );

		if (!$response['success']) {
            $this->showError('Error guardando datos', "Ha ocurrido un error guardando los datos del usuario.", 10000);
            exit;
        }

        // $this->notificationEmailQuote($response['quote']);

        $this->showModalCreateQuote = !$this->showModalCreateQuote;
        $this->showSuccess('Datos actualizados', $response['message'], 5000);
    }

    /**
     * Eliminar area
     * @author Hadik Chavez (ChivoDev) -  CofeeBlcks <cofeeblcks@gmail.com, chavezhadik@gmail.com>
     * @return void
     */
    public function destroy(): void
    {
        $response = (new DeleteQuote())->execute($this->quoteId);

        if( !$response['success'] ){
            $this->showDanger('Error eliminando cotización', "Ha ocurrido un error eliminando el cotización. " . $response['message'], 10000);
        }
        $this->showModalDeleteQuote = false;
        $this->resetInputs();
        $this->showSuccess('Cotización eliminada', 'Se ha eliminado la cotización exitosamente.', 5000);
    }

    // Metodos para abrir modales
    public function create(): void
    {
        $this->resetInputs();
        $this->createQuote = true;
        $this->inputDisabled = false;
        $this->showModalCreateQuote = !$this->showModalCreateQuote;
    }

    /**
     * Consultar información del usuario para visualizar en la modal y/o editar
     * @author Hadik Chavez (ChivoDev) -  CofeeBlcks <cofeeblcks@gmail.com, chavezhadik@gmail.com>
     * @param integer $quoteId
     * @return void
     */
    public function edit(int $quoteId)
    {
        $this->resetInputs();

        $quote = (new QuoteFinder())->execute(quoteId: $quoteId);

        $this->quoteData = [
            'description' => $quote->description,
            'dateQuote' => $quote->date_quote,
            'customerId' => $quote->customer_id,
            'withTotal' => $quote->with_total,
        ];

        $this->quoteDetailsData = $quote->quoteDetails->map(function($detail){
            return [
                'id' => $detail->id,
                'description' => $detail->description,
                'quantity' => $detail->quantity,
                'unitCost' => $detail->unit_cost,
            ];
        })->toArray();

        $this->quoteId = $quoteId;

        $this->createQuote = false;
        $this->inputDisabled = true;
        $this->readOnly = true;
        $this->showModalCreateQuote = !$this->showModalCreateQuote;
    }

    public function delete(int $quoteId): void
    {
        $quote = (new QuoteFinder())->execute(quoteId: $quoteId);

        $this->quoteId = $quoteId;
        $this->quoteConsecutive = $quote->consecutive;
        $this->showModalDeleteQuote = true;
    }

    public function viewPdf(int $quoteId)
    {
        $this->quoteId = $quoteId;
        $this->showModalViewPdf = !$this->showModalViewPdf;
    }
}
