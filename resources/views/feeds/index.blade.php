<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __("Feeds") }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 text-gray-900 divide-y-2">

				@if ($feeds->isNotEmpty())
					@foreach ($feeds as $feed)
					<div class="pt-6 mb-6">
						<h3 class="font-semibold text-lg hover:underline hover:decoration-gray-400">
							<a href="/feeds/{{ $feed->id }}">
								{{ $feed->site_title}}
							</a>
						</h3>
						<p class="mt-2 hover:underline hover:decoration-gray-400">
							<a href="{{ $feed->site_url }}" target="_blank">
								{{ $feed->site_url}}
								<img 
									class="inline" 
									width="10" 
									alt="External link font awesome" 
									src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6a/External_link_font_awesome.svg/512px-External_link_font_awesome.svg.png"
								>
							</a>
						</p>
						<p class="mt-4"><span class="font-medium">Last updated:</span> {{ $feed->updated_at->format('m/d/Y') }}</p>
					</div>
					@endforeach
				@else
					<div class="pt-6 mb-6">
						<h3 class="font-semibold text-lg">No feeds available</h3>
						<p class="mt-4">
							<a href="{{ route('feeds.create') }}" class="hover:underline hover:decoration-gray-400">Enter a new feed</a>
						</p>
					</div>
				@endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>