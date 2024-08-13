@props(['title' => ''])
@if ( !empty($title) )
    <div class="relative bg-customPrimary p-2 sm:p-4 rounded-md overflow-hidden mb-8 max-xs:mb-0">
        <!-- Content -->
        <div class="relative">
            <h1 class="text-md text-white mb-1"> {{ $title }} </h1>
        </div>
    </div>
@endif
