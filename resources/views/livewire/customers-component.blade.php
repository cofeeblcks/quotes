<div x-data="{
    inputDisabled: @entangle('inputDisabled').live,
    readOnly: @entangle('readOnly').live,
    createCustomer: @entangle('createCustomer'),
}" class="my-4 max-xs:mx-2 max-w-screen-2xl mx-auto">
        <x-overlay target="store, update, destroy"/>

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
                            {{ __('Crear cliente') }}
                        </x-button>
                    </div>
                </div>
            </div>
        </div>

        <div class="header-content bg-customPrimary rounded-lg py-4 px-7 my-4 flex justify-between">
            <div class="left">
                <h1 class="text-white font-semibold text-2xl max-xs:text-xl">Clientes</h1>
            </div>

            @if ( $customers && $customers->hasPages() )
                <div class="rigth flex items-end">
                    <p class="text-customBlack/70 text-sm font-semibold">Total registros en lista {{ $customers->total() }}</p>
                </div>
            @endif
        </div>

        @if ( count($customers) > 0 )
            <div class="grid grid-cols-3 gap-4">
                @foreach ($customers as $customer)
                    <div class="w-full rounded-lg bg-white duration-300 shadow-lg mb-4 border border-l-4">
                        {{-- Header --}}
                        <div class="w-full p-4 flex items-center justify-between">
                            <h1 class="text-lg font-bold text-gray-600">
                                {{ $customer->name }}
                            </h1>
                        </div>

                        {{-- Content --}}
                        <div class="w-full px-4 grid grid-cols-1 gap-4">
                            {{-- Datos --}}

                            <div class="flex flex-col max-md:col-span-3">
                                <div class="bg-customPrimary/20 py-1 px-2 rounded-lg text-customBlack">
                                    Correo electrónico: {{ $customer->email ?? '-' }}
                                </div>
                            </div>

                            <div class="flex flex-col max-md:col-span-3">
                                <div class="bg-customPrimary/20 py-1 px-2 rounded-lg text-customBlack">
                                    Número telefónico: {{ $customer->phone ?? '-' }}
                                </div>
                            </div>

                            <div class="flex flex-col max-md:col-span-3">
                                <div class="bg-customPrimary/20 py-1 px-2 rounded-lg text-customBlack">
                                    Dirección: {{ $customer->address ?? '-' }}
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="bg-gray-100 mt-4 p-4 rounded-lg grid grid-flow-col auto-cols-max gap-4 max-xl:grid-flow-row max-xl:auto-row-max justify-end">
                            <x-button wire:click="edit({{ $customer->id }})" class="max-xs:mt-5">
                                {{ __('Ver cliente') }}
                            </x-button>
                            <x-button wire:click="delete({{ $customer->id }})" class="bg-error max-xs:mt-5">
                                {{ __('Eliminar cliente') }}
                            </x-button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white hover:bg-[#F0F1F5] text-center rounded-lg h-16 shadow-md p-4">
                No hay registros.
            </div>
        @endif

        @if ( $customers->hasPages() )
            <div class="bg-white rounded-lg py-4 px-7 my-4">
                {{ $customers->links() }}
            </div>
        @endif

        {{-- Modal crear cliente --}}
        @if( $showModalCreateCustomer )
            <x-dialog-modal wire:model="showModalCreateCustomer" maxWidth="max-w-6xl" :backdrop="true">
                <x-slot name="title">
                    <div class="flex items-center justify-between">
                        <h1 class="text-[20px]">
                            {{ $createCustomer ? 'Datos cliente' : 'Editar cliente' }}
                        </h1>

                        <x-application-close-modal wire:click="$set('showModalCreateCustomer', false)" />
                    </div>
                </x-slot>

                <x-slot name="content">
                    <div class="bg-customGray shadow-lg p-8 max-md:p-2 rounded-lg my-2">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col col-span-2 border border-customPrimary rounded-lg p-4 mt-4">
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
                            </div>
                        </div>
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <div class="flex justify-between w-full">
                        <div class="buttons-left">
                            <template x-if="!createCustomer">
                                <div class="flex justify-end w-full">
                                    <x-button @click="inputDisabled = !inputDisabled" x-text="inputDisabled ? 'Editar datos' : 'Cancelar edición de datos'" class="bg-customBlack/20 !text-customBlack hover:bg-customBlack/50 {{ $inputDisabled ? '' : '!bg-error hover:!bg-error/80 !text-white' }}"></x-button>
                                </div>
                            </template>
                        </div>
                        <div class="buttons-rigth">
                            <div x-show="createCustomer">
                                <x-button wire:loading.attr="disabled" wire:click="store">
                                    {{ __('Guardar') }}
                                    <div wire:loading wire:target="store">
                                        <x-spinner size="w-5 h-5" class="ml-1"/>
                                    </div>
                                </x-button>
                            </div>
                            <div x-show="!inputDisabled && !createCustomer">
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

        {{-- modal eliminar cliente --}}
        @if ( $showModalDeleteCustomer )
            <x-dialog-modal wire:model="showModalDeleteCustomer" maxWidth="max-w-lg">
                <x-slot name="title">
                    <div class="flex items-center justify-between">
                        <h1 class="text-[20px]">
                            Eliminar cliente
                        </h1>

                        <x-application-close-modal wire:click="$set('showModalDeleteCustomer', false)" />
                    </div>
                </x-slot>

                <x-slot name="content">
                    <p class="text-[#393D40] text-[14px] manropeRegular">
                        Está a punto de eliminar la cliente <strong>{{ $customerName }}</strong>
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
</div>
