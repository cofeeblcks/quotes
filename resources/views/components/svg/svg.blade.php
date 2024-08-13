@props(['icon' => null])

@switch($icon)
    @case('svg-dashboard')
        <x-svg.sidebar.dashboard />
        @break

    @case('svg-trabajo-de-grado-en-linea')
        <x-svg.sidebar.trabajo-de-grado-en-linea />
        @break

    @case('svg-trabajo-de-grado-alterno')
        <x-svg.sidebar.trabajo-de-grado-alterno />
        @break

    @case('svg-politicas-de-graduacion')
        <x-svg.sidebar.politicas-de-graduacion />
        @break

    @case('svg-parametrizacion')
        <x-svg.sidebar.parametrizacion />
        @break
    @default
@endswitch
