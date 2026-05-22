@props(['title' => null, 'value' => null, 'icon' => null, 'footer' => null])

<div class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl shadow-sm hover:shadow-xl hover:shadow-emerald-500/5 transition-all group overflow-hidden relative">
    <!-- Decorative Blur -->
    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-full blur-3xl group-hover:bg-emerald-100 dark:group-hover:bg-emerald-800/30 transition-colors"></div>
    
    <div class="relative flex items-center justify-between mb-4">
        @if(isset($title_slot))
            {{-- Dynamic title slot: allows passing custom HTML like buttons --}}
            <div class="flex items-center justify-between w-full">
                {{ $title_slot }}
            </div>
        @elseif($title)
            <h5 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $title }}</h5>
        @endif
        @if($icon)
            <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl group-hover:scale-110 transition-transform text-emerald-600 dark:text-emerald-400">
                {!! $icon !!}
            </div>
        @endif
    </div>

    @if($value !== null)
        <div class="relative flex items-baseline">
            <span class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $value }}</span>
        </div>
    @endif
    
    <div class="mt-4 relative dark:text-gray-300">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-700 relative">
            {!! $footer !!}
        </div>
    @endif
</div>
