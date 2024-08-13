@props([
    'dataset', // Data to show in the select or path to the data
    'columnName' => 'name', // Column name to show in the select
    'columnId' => 'id', // Column name to use as id
    'params' => [], // Params to apply to the query
    'groupBy' => null, // Group by column name
    'multiple' => false, // Is multiple select
    'disabled' => false, // Is disabled
    'creation' => false, // Allow creation of new items
    'placeholder' => 'Seleccionar',
    'limit' => 0, // Allow limit selected items
])

@php
    $id = $attributes->wire('model')->value ?? 'select-one-' . time();
    // Incializar filtro
    if (!key_exists(str_replace('.','-',$id), $this->filtersSelectOne)) {
        $this->filtersSelectOne[str_replace('.','-',$id)] = '';
    }
    $search = $this->filtersSelectOne[str_replace('.','-',$id)] ?? '';
@endphp

<div x-data="{
    search: @entangle('filtersSelectOne.'.str_replace('.','-',$attributes->wire('model')->value)).live,
    open: false,
    model: @entangle($attributes->wire('model')).live,
    multiple: {{ json_encode($multiple)}},
    creation: {{ json_encode($creation)}},
    limit: {{ json_encode($limit)}},
    checkFunc: null,
    check(value) {
        let self = this;
        if (this.checkFunc) {
            clearTimeout(this.checkFunc);
        }
        this.checkFunc = setTimeout(() => {
            self.model = (self.multiple && !Array.isArray(self.model)) ? [] : self.model;
            if (self.multiple) {
                if( self.limit > 0 && self.model.length >= self.limit && !self.model.includes(value) ){
                    return;
                }
                if (self.model.includes(value)) {
                    self.model = self.model.filter(item => item !== value);
                } else {
                    self.model.push(value);
                }
            } else {
                if (self.model == value) {
                    self.model = null;
                } else {
                    self.model = value;
                }
            }
        }, 100);
    },
    validateChecked(value) {
        this.model = (this.multiple && !Array.isArray(this.model)) ? [] : this.model;
        if (!this.multiple) {
            return this.model == value;
        }
        return this.model.includes(value);
    },
    addCustom() {
        if (!this.creation) {
            return;
        }
        if (this.multiple) {
            this.model.push(this.search);
        } else {
            this.model = this.search;
        }
    }
}" x-on:click.away="open = false" @if (!$disabled) x-on:click="$refs.search.focus()" @endif class="relative">
    <div x-ref="input" x-on:click="open = true" class="flex items-center cursor-pointer justify-between w-full relative rounded-lg border border-customGrayDark hover:border-customPrimary @if ($errors->has($attributes->wire('model')->value)) border-[#FD4A4A] @endif @if ($disabled) bg-customBlack/10 @else bg-white @endif px-[2px] py-[2px] min-h-[42px]">
        <div class="flex items-center flex-wrap">
            @php
                $hasSelected = false;
            @endphp
            @foreach ($this->getSelectedItemsSelectOne($id, $dataset, $columnId, $columnName, $groupBy) as $item)
                @php
                    $hasSelected = true;
                @endphp
                <div class="bg-customPrimary/50 px-1.5 py-1 rounded-lg flex justify-between items-center } mx-[3px] my-[3px]">
                    <span class="text-[#393D40] select-none text-[14px] line-clamp-1 text-balance" title="@if ($groupBy && ($item[$groupBy]??null)) {{$item[$groupBy]}}: @endif {{$item[$columnName]}}">
                        @if ($groupBy && ($item[$groupBy]??null))
                            {{$item[$groupBy]}}:
                        @endif
                        {{$item[$columnName]}}
                    </span>
                    @if (!$disabled)
                        <span x-on:click="check({{json_encode($item[$columnId])}})" class="cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 flex-none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </span>
                    @endif
                </div>
            @endforeach
            @if (!$hasSelected && !$disabled)
                <span class="text-[#242424]/50 text-[16px] px-2">
                    {{ $placeholder }}
                </span>
            @endif
        </div>

        <div class="flex-none mr-2">
            <div wire:loading.remove wire:target="{{$id}}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </div>
            <div wire:loading wire:target="{{$id}}">
                <div role="status" class="mx-2">
                    <svg aria-hidden="true" class="inline w-6 h-6 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    @if (!$disabled)
        <div x-show="open" x-anchor.no-style="$refs.input" x-cloak
            x-bind:style="{ position: 'absolute', top: $anchor.y+'px', left: '0px', zIndex: 1000}"
            class="bg-customGray border border-customBlack/50 p-2 rounded-lg">

            <div class=" flex justify-center">
                <div class="flex-grow relative flex">
                    <div role="status" class="absolute right-[5px] top-[12px]">
                        <div wire:loading wire:target="filtersSelectOne">
                            <svg aria-hidden="true" class="inline w-6 h-6 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                            </svg>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <input placeholder="Búsqueda" x-model.debounce.500ms="search" x-ref="search" x-show="open" type="search" class="border border-[#E2E8F0] w-full rounded-lg my-1 text-[14px] min-w-[200px] h-[38px] focus:ring-0 px-2">
                </div>
                @if (!empty($search) && $creation)
                    <button class="flex-shrink my-1 py-1 px-4 bg-customPrimary text-white text-[1rem] rounded-md ml-2" x-on:click="addCustom">
                        Añadir
                    </button>
                @endif
            </div>
            <div class="select-none max-h-[340px] overflow-y-auto max-w-lg">
                @php
                    $hasData = false;
                @endphp
                @if ($creation)
                    <span class="text-xs text-customPrimary">Permite nuevas opciones</span>
                @endif
                @if ( $limit > 0 )
                    <span class="text-xs text-customPrimary">Solo se permite seleccionar {{ $limit }} registros</span>
                @endif
                @foreach ($this->getGroupedDataSelectOne($dataset, $groupBy, $search, $columnName, $params) as $groupName => $items)
                    <div class="flex flex-col my-1 z-10">
                        @if (!empty($groupName))
                            <span class="text-[#2A2A2A] text-[14px] font-semibold my-1 px-2">
                                {{ $groupName }}
                            </span>
                        @endif
                        @foreach ($items as $item)
                            @php
                                $hasData = true;
                            @endphp
                            <div class="flex items-center pl-3 cursor-pointer w-full hover:bg-customGray py-1" x-data='{hasGroup: "{{ json_encode(!empty($groupName)) }}"}' x-on:click="check({{json_encode($item[$columnId])}})">
                                <div class="flex justify-center items-center">
                                    @if ($multiple)
                                        <input type="checkbox" readonly :checked="validateChecked({{json_encode($item[$columnId])}})" class="border-2 border-[#242424] rounded text-customPrimary cursor-pointer focus:ring-0">
                                    @else
                                        <input value="{{json_encode($item[$columnId])}}" type="radio" readonly :checked="validateChecked({{json_encode($item[$columnId])}})" class="border-2 border-[#242424] rounded-full text-customPrimary cursor-pointer focus:ring-0">
                                    @endif
                                </div>
                                <label class="text-[#2A2A2A] text-[14px] cursor-pointer relative pl-2 pr-2">
                                    {{$item[$columnName]}}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                @if (!$hasData)
                    <div class="px-4">
                        @if (empty($search))
                            No hay registros disponibles
                        @else
                            No se encontraron resultados
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

