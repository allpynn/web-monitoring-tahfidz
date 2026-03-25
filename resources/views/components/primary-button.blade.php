<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-emerald-700 dark:bg-emerald-600 border border-transparent rounded-2xl font-bold text-xs text-white uppercase tracking-widest hover:bg-emerald-800 dark:hover:bg-emerald-500 focus:bg-emerald-800 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-lg shadow-emerald-100 dark:shadow-none']) }}>
    {{ $slot }}
</button>
