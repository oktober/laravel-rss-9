<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __("Latest Feeds") }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @forelse ($feeds as $feed)
                        <div class="mb-4 border-b-2 border-gray-100 pb-2">
                            <h3 class="font-semibold text-lg">
                                <a href="{{ route('feeds.show', $feed) }}" class="hover:underline hover:decoration-gray-400">{{ $feed->site_title }}</a>
                            </h3>
                        </div>

                        <section class="mb-6 flex flex-col md:flex-row space-y-6 md:space-y-0 md:justify-between md:space-x-6">
                            @foreach ($feed->entries as $entry)
                                {{-- Only want to show the first 3 entries for a feed --}}
                                @if ($loop->index > 2) 
                                    @break
                                @endif
                                <article class="px-4 md:w-1/3">
                                    <h4 class="font-semibold">
                                        <a href="/entry/{{ $entry->id }}" class="hover:underline hover:decoration-gray-400">{{ $entry->entry_title }}</a>
                                    </h4>
                                    <p>{{ $entry->entry_teaser }}</p>
                                </article>
                            @endforeach
                        </section>

                        @empty
                        <div class="mb-6">
                            <h3 class="font-semibold text-lg">No feeds available</h3>
                            <p class="mt-4">
                                <a href="{{ route('feeds.create') }}" class="hover:underline hover:decoration-gray-400">Enter a new feed</a>
                            </p>
                        </div>

                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>