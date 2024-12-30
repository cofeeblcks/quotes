<div x-data="{
    inputDisabled: @entangle('inputDisabled').live,
    readOnly: @entangle('readOnly').live,
    createQuote: @entangle('createQuote'),
}" class="my-4 max-xs:mx-2 max-w-screen-2xl mx-auto">
        <x-overlay target="store, update, destroy, viewPdf"/>

        <div class="header-content relative my-8 max-xs:mt-4 rounded-lg border border-dashed border-customPrimary/50 p-4">
            <span class="absolute -top-4 left-3 px-2 py-1 text-md bg-customGray">Filtros</span>
            <div class="flex items-end mb-4 justify-between flex-wrap">
                <div class="flex items-end flex-wrap max-md:w-full max-md:justify-between max-xs:flex-nowrap">
                    <div class="flex flex-col mr-4">
                        <x-label for="recordsPerPage" hasError="recordsPerPage" value="{{ __('Registros por página') }}" />
                        <x-select-one
                            wire:model.live='recordsPerPage'
                            :dataset="$this->getAvailableRecordsPerPage()"
                            :multiple="false"
                        />
                    </div>
                </div>

                <div class="flex items-end flex-wrap max-md:mt-5 max-md:w-full max-md:justify-between">
                    <x-filter class="max-xs:w-full" model="filterSearch" />

                    <div class="flex items-center">
                        <x-refresh class="ml-3 bg-customPrimary max-xs:mt-5"/>

                        <x-button wire:click="create" class="ml-3 max-xs:mt-5">
                            {{ __('Crear cotización') }}
                        </x-button>
                    </div>
                </div>
            </div>
        </div>

        <div class="header-content bg-customPrimary rounded-lg py-4 px-7 my-4 flex justify-between">
            <div class="left">
                <h1 class="text-white font-semibold text-2xl max-xs:text-xl">Cotizaciones</h1>
            </div>

            @if ( $quotes && $quotes->hasPages() )
                <div class="rigth flex items-end">
                    <p class="text-customBlack/70 text-sm font-semibold">Total registros en lista {{ $quotes->total() }}</p>
                </div>
            @endif
        </div>

        @if ( count($quotes) > 0 )
            @foreach ($quotes as $quote)
                <div class="w-full rounded-lg bg-white duration-300 shadow-lg mb-4 border border-l-4">
                    {{-- Header --}}
                    <div class="w-full p-4 flex items-center justify-between">
                        <h1 class="text-lg font-bold text-gray-600">
                            Cotización {{ $quote->consecutive }} / {{ $quote->customer->name }}
                        </h1>
                    </div>

                    {{-- Content --}}
                    <div class="w-full px-4 grid grid-cols-3 max-xl:grid-cols-1 gap-4">
                        {{-- Datos --}}
                        <div class="flex flex-col max-md:col-span-3">
                            <p>Fecha de registro</p>
                            <div class="bg-customPrimary/20 ml-2 cursor-pointer py-1 px-2 rounded-lg text-customBlack my-2">
                                {{ $quote->date_quote }}
                            </div>
                        </div>

                        <div class="flex flex-col max-md:col-span-3">
                            <p>Cliente</p>
                            <div class="bg-customPrimary/20 ml-2 cursor-pointer py-1 px-2 rounded-lg text-customBlack my-2">
                                {{ $quote->customer->name }} / {{ $quote->customer->email }} / {{ $quote->customer->phone }}
                            </div>
                        </div>

                        <div class="flex flex-col max-md:col-span-3">
                            <p>Total</p>
                            <div class="bg-customPrimary/20 ml-2 cursor-pointer py-1 px-2 rounded-lg text-customBlack my-2">
                                $ {{ number_format($quote->total, 0, ',', '.') }}
                            </div>
                        </div>

                        @if ( $quote->description )
                            <div class="flex flex-col col-span-3">
                                <p>Descripción</p>
                                <div class="bg-customPrimary/20 ml-2 cursor-pointer py-1 px-2 rounded-lg text-customBlack my-2">
                                    {{ $quote->description }}
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col col-span-3">
                            <x-table :ths="['Descripción', 'Cantidad', 'Valor unitario', 'Valor total']">
                                <x-slot name="trs">
                                    @foreach ($quote->quoteDetails as $detail)
                                        <tr class="text-customBlack h-8 text-sm text-center hover:bg-customGray/50 odd:bg-customGray even:bg-customPrimary/20">
                                            <td class="w-full px-6 py-3 @if ($loop->first) rounded-tl-lg @endif @if ($loop->last) rounded-bl-lg @endif">
                                                {{ $detail->description }}
                                            </td>
                                            <td class="w-full px-6 py-3">
                                                {{ $detail->quantity }}
                                            </td>
                                            <td class="w-full px-6 py-3">
                                                $ {{ number_format($detail->unit_cost, 0, ',', '.') }}
                                            </td>
                                            <td class="w-full px-6 py-3 @if ($loop->first) rounded-tr-lg @endif @if ($loop->last) rounded-br-lg @endif">
                                                $ {{ number_format(($detail->quantity * $detail->unit_cost), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </x-slot>
                            </x-table>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-100 mt-4 p-4 rounded-lg grid grid-flow-col auto-cols-max gap-4 max-xl:grid-flow-row max-xl:auto-row-max justify-end">
                        <x-button wire:click="edit({{ $quote->id }})" class="max-xs:mt-5">
                            {{ __('Ver cotización') }}
                        </x-button>
                        <x-button wire:click="viewPdf({{ $quote->id }})" class="bg-success max-xs:mt-5">
                            {{ __('Ver documento') }}
                        </x-button>
                        <x-button wire:click="delete({{ $quote->id }})" class="bg-error max-xs:mt-5">
                            {{ __('Eliminar cotización') }}
                        </x-button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white hover:bg-[#F0F1F5] text-center rounded-lg h-16 shadow-md p-4">
                No hay registros.
            </div>
        @endif

        @if ( $quotes->hasPages() )
            <div class="bg-white rounded-lg py-4 px-7 my-4">
                {{ $quotes->links() }}
            </div>
        @endif

        {{-- Modal crear proyecto --}}
        @if( $showModalCreateQuote )
            <x-dialog-modal wire:model="showModalCreateQuote" maxWidth="max-w-6xl" :backdrop="true">
                <x-slot name="title">
                    <div class="flex items-center justify-between">
                        <h1 class="text-[20px]">
                            {{ $createQuote ? 'Datos cotización' : 'Editar cotización' }}
                        </h1>

                        <x-application-close-modal wire:click="$set('showModalCreateQuote', false)" />
                    </div>
                </x-slot>

                <x-slot name="content">
                    <div class="bg-customGray shadow-lg p-8 max-md:p-2 rounded-lg my-2">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="flex flex-col col-span-3">
                                <x-label class="" for="quoteData.description" hasError="quoteData.description" value="{{ __('Descripción') }}" />
                                <x-textarea wire:model="quoteData.description" x-bind:disabled="inputDisabled" class="bg-white disabled:bg-customBlack/10" name="quoteData.description" id="quoteData.description"/>
                            </div>

                            @if( !$newCustomer )
                                <div class="flex flex-col max-md:col-span-2">
                                    <x-label class="after:content-['*'] after:ml-0.5" for="quoteData.customerId" hasError="quoteData.customerId" value="{{ __('Cliente') }}" />
                                    <div class="flex flex-col w-full">
                                        <x-select-one
                                            wire:model.live="quoteData.customerId"
                                            dataset="App\Actions\Customers\CustomersList"
                                            :multiple="false"
                                            :creation="true"
                                            :params="[
                                                'recordsPerPage' => 10,
                                                'output' => 'paginate'
                                            ]"
                                            :disabled="$readOnly"
                                        />
                                    </div>
                                </div>
                            @endif

                            <div class="flex flex-col max-md:col-span-2">
                                <x-label class="after:content-['*'] after:ml-0.5" for="quoteData.dateQuote" hasError="quoteData.dateQuote" value="{{ __('Fecha de cotización') }}" />
                                <x-datepicker wire:model="quoteData.dateQuote" :disabled="$readOnly" />
                            </div>

                            <div class="flex flex-col max-md:col-span-2">
                                <x-label class="after:content-['*'] after:ml-0.5" for="quoteData.withTotal" hasError="quoteData.withTotal" value="{{ __('Totalizar?') }}" />
                                <x-switch wire:model="quoteData.withTotal" :disabled="$inputDisabled" class="disabled:bg-black/10" id="quoteData.withTotal"/>
                            </div>

                            @if( $newCustomer )
                                <div class="flex flex-col col-span-3 border border-customPrimary rounded-lg p-4 mt-4">
                                    <h3 class="w-full text-center font-semibold text-xl text-customBlack mb-4">Datos del cliente</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="flex flex-col max-md:col-span-2">
                                            <x-label class="after:content-['*'] after:ml-0.5" for="customerData.name" hasError="customerData.name" value="{{ __('Nombre del cliente') }}"/>

                                            <x-input wire:model="customerData.name" x-bind:disabled="inputDisabled" type="text" class="bg-white disabled:bg-customBlack/10 w-full" />
                                        </div>
                                        <div class="flex flex-col max-md:col-span-2">
                                            <x-label class="after:content-['*'] after:ml-0.5" for="customerData.email" hasError="customerData.email" value="{{ __('Correo electrónico') }}"/>

                                            <x-input wire:model="customerData.email" x-bind:disabled="inputDisabled" type="text" class="bg-white disabled:bg-customBlack/10 w-full" />
                                        </div>
                                        <div class="flex flex-col max-md:col-span-2">
                                            <x-label class="" for="customerData.phone" hasError="customerData.phone" value="{{ __('Número telefónico') }}"/>

                                            <x-input wire:model="customerData.phone" x-bind:disabled="inputDisabled" type="text" class="bg-white disabled:bg-customBlack/10 w-full" />
                                        </div>
                                        <div class="flex flex-col max-md:col-span-2">
                                            <x-label class="" for="customerData.address" hasError="customerData.address" value="{{ __('Dirección') }}"/>

                                            <x-input wire:model="customerData.address" x-bind:disabled="inputDisabled" type="text" class="bg-white disabled:bg-customBlack/10 w-full" />
                                        </div>
                                    </div>
                                    <div class="w-full flex justify-end">
                                        <x-button wire:click="cancelRegisterCustomerAdd" class="mt-5 bg-error">
                                            {{ __('Cancelar') }}
                                        </x-button>
                                    </div>
                                </div>
                            @endif

                            <div class="flex flex-col col-span-3">
                                <div class="p-4 max-md:p-0">
                                    <x-label class="w-full text-center font-semibold text-xl text-customBlack mb-4" value="{{ __('Detalle de cotización') }}" />
                                    <div class="grid grid-cols-1 gap 4">
                                        <x-table :ths="['Descripción', 'Cantidad', 'Valor unitario', 'Borrar']">
                                            <x-slot name="trs">
                                                @foreach ($quoteDetailsData as $index => $detail)
                                                    <tr>
                                                        <td class="w-full">
                                                            <x-input wire:model="quoteDetailsData.{{ $index }}.description" x-bind:disabled="inputDisabled" class="bg-white disabled:bg-customBlack/10 w-full" />
                                                        </td>
                                                        <td class="w-full">
                                                            <x-input wire:model="quoteDetailsData.{{ $index }}.quantity" x-bind:disabled="inputDisabled" class="bg-white disabled:bg-customBlack/10 w-full" x-mask:dynamic="'9999999'" />
                                                        </td>
                                                        <td class="w-full">
                                                            <x-input wire:model="quoteDetailsData.{{ $index }}.unitCost" x-bind:disabled="inputDisabled" class="bg-white disabled:bg-customBlack/10 w-full" x-mask:dynamic="$money($input, ',')" />
                                                        </td>
                                                        <td class="w-full">
                                                            @if ( !$inputDisabled )
                                                                @if ( count($quoteDetailsData) > 1 )
                                                                    <div class="bg-error cursor-pointer text-white rounded-lg flex items-center justify-center py-2 mx-2" wire:click="removeRow({{ $index }})">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                        </svg>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    </div>
                                </div>
                                @if ( !$inputDisabled )
                                    <x-divider />
                                    <div class="w-full flex justify-end">
                                        <x-button wire:click="addRow" class="mt-5">
                                            {{ __('Agregar item') }}
                                        </x-button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <div class="flex justify-between w-full">
                        <div class="buttons-left">
                            <template x-if="!createQuote">
                                <div class="flex justify-end w-full">
                                    <x-button @click="inputDisabled = !inputDisabled" x-text="inputDisabled ? 'Editar datos' : 'Cancelar edición de datos'" class="bg-customBlack/20 !text-customBlack hover:bg-customBlack/50 {{ $inputDisabled ? '' : '!bg-error hover:!bg-error/80 !text-white' }}"></x-button>
                                </div>
                            </template>
                        </div>
                        <div class="buttons-rigth">
                            <div x-show="createQuote">
                                <x-button wire:loading.attr="disabled" wire:click="store">
                                    {{ __('Guardar') }}
                                    <div wire:loading wire:target="store">
                                        <x-spinner size="w-5 h-5" class="ml-1"/>
                                    </div>
                                </x-button>
                            </div>
                            <div x-show="!inputDisabled && !createQuote">
                                <x-button wire:loading.attr="disabled" wire:click="update">
                                    {{ __('Guardar cambios') }}
                                    <div wire:loading wire:target="update">
                                        <x-spinner size="w-5 h-5" class="ml-1"/>
                                    </div>
                                </x-button>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-dialog-modal>
        @endif

        {{-- modal eliminar cotización --}}
        @if ( $showModalDeleteQuote )
            <x-dialog-modal wire:model="showModalDeleteQuote" maxWidth="max-w-[90%] lg:max-w-[50%]">
                <x-slot name="title">
                    <div class="flex items-center justify-between">
                        <h1 class="text-[20px]">
                            Eliminar cotización
                        </h1>

                        <x-application-close-modal wire:click="$set('showModalDeleteQuote', false)" />
                    </div>
                </x-slot>

                <x-slot name="content">
                    <p class="text-[#393D40] text-[14px] manropeRegular">
                        Está a punto de eliminar la cotización <strong>{{ $quoteConsecutive }}</strong>
                        <br><br>
                        Esta acción es irreversible y la información no podrá ser recuperada.
                    </p>
                </x-slot>

                <x-slot name="footer">
                    <x-button wire:click="destroy">
                        {{ __('Eliminar') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>
        @endif

        {{-- Modal PDF --}}
        @if( $showModalViewPdf )
            <livewire:components.document-pdf-component :quoteId="$quoteId" routeName="Cotizaciones" />
        @endif
</div>
