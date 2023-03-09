<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight min-w-max">
        {{ __("Add New Feed") }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('feeds.store') }}">
                        @csrf

                        <div class="min-w-max">
                            <div>
                                <label class="inline-block font-semibold text-lg whitespace-nowrap" for="site_url">Enter a site URL</label>
                                <div class="relative mt-2 rounded-md shadow-sm w-full max-w-2xl">
                                    <input 
                                        class="block w-full max-w-2xl rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 placeholder:italic sm:text-sm sm:leading-6"
                                        type="text" 
                                        name="site_url" 
                                        id="site_url"
                                        placeholder="e.g. https://test.com"
                                        value="{{ old('site_url') }}"
                                        maxlength="255"
                                        required
                                    >
                                </div>
                                <!-- To show any validation or session errors -->
                                <p class="text-sm text-red-500 mt-2">{{ $errors->first('site_url') ?? '' }}{{ session('error') ?? '' }}</p>
                            </div>

                            <div class="mt-4 flex justify-end max-w-2xl">
                                <x-primary-button>{{ __('Add Feed') }}</x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>