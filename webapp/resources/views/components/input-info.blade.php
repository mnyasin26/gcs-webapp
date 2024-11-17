{{-- @props(['message']) --}}

@if ($value)
    <p {{ $attributes->merge(['class' => 'text-[10px] text-gray-700 dark:text-gray-300']) }}>{{ $value }}</p>
@endif
