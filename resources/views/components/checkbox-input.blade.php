@props(['name', 'id'])

<input {{
    $attributes->merge([
        'type' => 'checkbox',
        'id' => $id,
        'name' => $name,
        'class' => 'w-4 h-4 align-middletext-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600'
        ])
    }}
/>
