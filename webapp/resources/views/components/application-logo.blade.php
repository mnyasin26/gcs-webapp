@isset($app->image)
    <img {{ $attributes->merge([
        'class' => 'rounded-full border-0 transition-[0.4s]',
        'src' => asset($app->image),
        'alt' => 'logo'
        ]) }}>
@endisset
