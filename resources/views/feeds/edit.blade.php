<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __("Update Feed") }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('feeds.update', $feed) }}">
                        @csrf
                        @method('PUT')

                        <div class="min-w-fit">
                            <label class="inline-block font-semibold text-lg whitespace-nowrap" for="site_title">Feed Name</label>
                                <input 
                                    class="block w-full max-w-2xl rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
                                    type="text" 
                                    name="site_title" 
                                    id="site_title" 
                                    value="{{ $feed->site_title }}"
                                />
                                @error('site_title')
                                    <p class="">{{ $errors->first('site_title') }}</p>
                                @enderror
                        </div>

                        <div class="flex justify-end mt-4 min-w-fit max-w-2xl">
                            <x-danger-button
                                class="mr-4"
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-feed-deletion')"
                            >
                                {{ __('Delete Feed') }}
                            </x-danger-button>

                            <x-secondary-button 
                                class="mr-4"
                                x-data="" 
                                x-on:click="window.location='{{ route('feeds.show', $feed) }}'"
                            >
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>


                    <x-modal name="confirm-feed-deletion" focusable>
                        <form method="post" action="{{ route('feeds.destroy', $feed) }}" class="p-6">
                            @csrf
                            @method('delete')

                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Are you sure you want to delete this feed?') }}
                            </h2>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">
                                    {{ __('Cancel') }}
                                </x-secondary-button>

                                <x-danger-button class="ml-3">
                                    {{ __('Delete Feed') }}
                                </x-danger-button>
                            </div>
                        </form>
                    </x-modal>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>