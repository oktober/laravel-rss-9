<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <a href="{{ route('feeds.show', $entry->feed->id) }}">{{ $entry->feed->site_title }}</a>
        </h2>
        <p class="text-sm mt-2 hover:underline hover:decoration-gray-400">
            <a 
                href="{{ route('feeds.show', $entry->feed->id) }}" 
                class="underline decoration-gray-400 hover:underline hover:decoration-indigo-700 hover:text-indigo-700 focus:underline focus:decoration-indigo-700 focus:text-indigo-700"
            >
                Back to feed
            </a>
        </p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="font-semibold text-lg">{{ $entry->entry_title }}</h3>

                        <p class="text-sm my-2 hover:underline hover:decoration-gray-400">
                            <a href="{{ $entry->entry_url }}" target="_blank">
                                {{ $entry->entry_url }}
                                <img 
                                    class="inline" 
                                    width="10" 
                                    alt="External link font awesome" 
                                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6a/External_link_font_awesome.svg/512px-External_link_font_awesome.svg.png"
                                >
                            </a>
                        </p>
                        <p class="text-sm"><span class="font-semibold">Posted:</span> {{ $entry->entry_last_updated->toFormattedDateString() }}</p>

                        <hr class="my-4" />

                        <iframe 
                            srcdoc="<!DOCTYPE html>
                                <head>
                                    <style type=text/css>
                                    body { 
                                        font-family: Figtree, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif;
                                        color: rgb(17 24 39);
                                        }
                                    a {
                                        color: inherit;
                                    }
                                    a:focus, a:hover {
                                        color: #4338ca;
                                        text-decoration-color: #4338ca;
                                    }
                                    </style>
                                </head>
                                <body>{{ $entry->entry_content }}</body>"
                            frameborder="0"
                            class="h-screen w-full"
                            >
                            <p>Your browser does not support iframes.</p>
                        </iframe>

                        <hr class="my-4" />

                        <p class="text-sm">
                            <a 
                                href="{{ route('feeds.show', $entry->feed->id) }}"
                                class="underline decoration-gray-400 hover:underline hover:decoration-indigo-700 hover:text-indigo-700 focus:underline focus:decoration-indigo-700 focus:text-indigo-700"
                            >
                                Back to Feed
                            </a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>