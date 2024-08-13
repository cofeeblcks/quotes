@props(['ths' => [], 'trs' => null, 'otherClass' => '', 'padding' => ''])
<div class="w-full overflow-x-auto h-full {{ $otherClass }}">
    <table class="table-auto border-collapse w-full text-sm">
        <thead>
            <tr class="text-white bg-customPrimary h-8 text-xs text-center uppercase shadow-md">
                @foreach ($ths as $th)
                    @if ($loop->first)
                        <th class="px-6 py-3 text-center rounded-l-lg whitespace-nowrap">{{ $th }}</th>
                    @elseif($loop->last)
                        <th class="px-6 py-3 text-center rounded-r-lg whitespace-nowrap">{{ $th }}</th>
                    @else
                        <th class="px-6 py-3 text-center whitespace-nowrap">{{ $th }}</th>
                    @endif
                @endforeach
            </tr>

            <tr>
                <th class="py-2 {{ $padding }}"></th>
            </tr>
        </thead>
        <tbody class="text-[#242424] normal-case text-xs">
            @if ($trs == null || ($trs == '' || $trs == '<!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->' || empty($trs)))
                <tr class="bg-white hover:bg-[#F0F1F5] text-center rounded-lg h-16 shadow-md">
                    <td colspan="{{ count($ths) }}" class="p-4 rounded-lg">
                        No hay registros.
                    </td>
                </tr>
            @else
                {{ $trs }}
            @endif
        </tbody>
    </table>
</div>

{{-- Footer table --}}
@if ( isset($footer) )
    <div class="bg-white rounded-lg py-4 px-7 my-4">
        {{ $footer }}
    </div>
@endif
