@props([
    'type' => 'success',
    'message' => 'Message Success'
])

<div x-data="{ open: true }" x-show="open" x-transition>
    <div class="flex items-center p-4 mb-4 text-green-800 border-t-4 border-green-300 bg-green-50">
        <svg class="w-4 h-4 mt-0.5 md:mt-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>

        <div class="ms-2 text-sm">
            {{ $message }}
        </div>

        <button @click="open = false" class="ms-auto text-green-900 hover:text-green-700">
            ✕
        </button>
    </div>
</div>
