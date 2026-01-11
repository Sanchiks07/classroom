@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200 rounded-md shadow-sm']) }}>
