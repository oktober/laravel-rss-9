<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $feed->site_title }}
        </h2>
        <p class="mt-2 hover:underline hover:decoration-gray-400">
            <a href="{{ $feed->site_url }}" target="_blank" rel="noopener">
                {{ $feed->site_url}}
                <img 
                    class="inline" 
                    width="10" 
                    alt="External link font awesome" 
                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6a/External_link_font_awesome.svg/512px-External_link_font_awesome.svg.png"
                >
            </a>
        </p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <p class="text-sm"><a 
                            href="{{ route('feeds.edit', $feed) }}" 
                            class="underline decoration-gray-400 hover:underline hover:decoration-indigo-700 hover:text-indigo-700 focus:underline focus:decoration-indigo-700 focus:text-indigo-700"
                            >
                            Edit This Feed
                        </a></p>
                    </div>

                    @if ($feed->entries->isNotEmpty())
                        <div class="divide-y-2">
                            @foreach ($feed->entries as $entry)
                            <div class="pt-4 mb-6">
                                <h3 class="font-semibold text-lg">
                                    <a href="{{ route('entries.show', $entry) }}" class="underline decoration-gray-400 hover:underline hover:decoration-indigo-700 hover:text-indigo-700 focus:underline focus:decoration-indigo-700 focus:text-indigo-700">
                                        {{ $entry->entry_title }}
                                    </a>
                                </h3>
                                <p class="text-sm">Posted: {{ $entry->entry_last_updated->diffForHumans() }}</p>

                                <p class="mt-2">{{ $entry->entry_teaser }}</p>
                                <p class="mt-2">
                                    <a href="{{ route('entries.show', $entry) }}" class="underline decoration-gray-400 hover:underline hover:decoration-indigo-700 hover:text-indigo-700 focus:underline focus:decoration-indigo-700 focus:text-indigo-700">
                                        Read More
                                    </a>
                                </p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mb-6">
                            <h3 class="font-semibold text-lg">No entries available</h3>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>