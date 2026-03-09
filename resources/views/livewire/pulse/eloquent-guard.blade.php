<x-pulse::card :cols="$cols" :rows="$rows" class="p-6">
    <x-pulse::card-header name="Eloquent Guard Alerts" title="N+1 and Slow Queries detected by Maxis">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
            </svg>
        </x-slot:icon>
    </x-pulse::card-header>

    <div class="mt-6 overflow-y-auto max-h-full">
        @if ($alerts->isEmpty())
            <x-pulse::no-results />
        @else
            <div class="grid grid-cols-1 gap-4">
                @foreach ($alerts as $alert)
                    <div class="flex items-center justify-between gap-3 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                        <div class="flex-1 overflow-hidden">
                            <div class="flex items-center gap-2">
                                @if(str_contains($alert->type, 'nPlusOne'))
                                    <span class="px-2 py-0.5 text-xs font-bold text-orange-700 bg-orange-100 rounded-full dark:bg-orange-900/30 dark:text-orange-400">🚨 N+1</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs font-bold text-red-700 bg-red-100 rounded-full dark:bg-red-900/30 dark:text-red-400">🔥 Slow</span>
                                @endif
                                <code class="text-[10px] text-gray-500 truncate font-mono">{{ $alert->key }}</code>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            {{ number_format($alert->value) }}{{ str_contains($alert->type, 'slow') ? 'ms' : 'x' }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-pulse::card>
