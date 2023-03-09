<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __("Add New Feed") }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('feeds.store') }}">
                        @csrf

                        <div>
                            <label class="inline-block font-semibold text-lg whitespace-nowrap" for="site_url">Site URL</label>
                            <div class="relative mt-2 rounded-md shadow-sm w-full max-w-2xl">
                                <input 
                                    class="block w-full max-w-2xl rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    type="text" 
                                    name="site_url" 
                                    id="site_url"
                                    value="{{ old('site_url') }}"
                                    maxlength="255"
                                >
                            </div>
                            @error('site_url')
                                <p class="">{{ $errors->first('site_url') }}</p>
                            @enderror
                            @if (session('error'))
                                <p class="">{{ session('error') }}</p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <button 
                                type="submit" 
                                class="group relative rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                            Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>