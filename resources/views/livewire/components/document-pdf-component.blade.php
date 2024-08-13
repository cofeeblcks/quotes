<div>
    @if ( $showModalViewPdf )
        <x-dialog-modal wire:model="showModalViewPdf" maxWidth="max-w-6xl" :backdrop="true">
            <x-slot name="title">
                <div class="flex items-center justify-between">
                    <h1 class="text-semibold">
                        Documento cotización {{ $quoteConsecutive }}
                    </h1>

                    <x-application-close-modal wire:click="$set('showModalViewPdf', false)" />
                </div>
            </x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-1 gap-4">
                    <object width="100%" height="600" data="{{ $urlPdf }}" type="application/pdf"></object>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-button wire:loading.attr="disabled" wire:click="sendNotification">
                    {{ __('Enviar por correo electrónico') }}
                    <div wire:loading wire:target="sendNotification">
                        <x-spinner size="w-5 h-5" class="ml-1"/>
                    </div>
                </x-button>
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
