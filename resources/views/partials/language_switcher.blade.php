<div x-data="{ open: false }" class="relative inline-block text-left">

    <!-- Trigger -->
    <button @click="open = !open"
        class="flex items-center gap-2 px-3 py-2 text-sm font-medium   hover:bg-gray-50 focus:outline-none">

        🌐 {{ $current_locale }}

        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
        </svg>
    </button>


    <div x-show="open" @click.outside="open = false" x-transition
        class="absolute bg-gray-200 right-0 mt-2 w-32  shadow-lg z-50">

        @foreach ($available_locales as $locale_name => $available_locale)
            @if ($available_locale !== $current_locale)
                <a href="{{ url('language/' . $available_locale) }}" class="block px-4 py-2 text-sm hover:bg-gray-100">
                    {{ $locale_name }}
                </a>
            @endif
        @endforeach
    </div>
</div>
