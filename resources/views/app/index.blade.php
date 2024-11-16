<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Center Point') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('abort'))
                        <div id="alert-info" class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                            </svg>
                            <span class="sr-only">Info</span>
                            <div class="ml-3 text-sm font-medium">
                                {{ session('abort') }}.
                            </div>
                        </div>
                    @endif
                    @if (session('success'))
                        <div id="alert-info" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                            </svg>
                            <span class="sr-only">Info</span>
                            <div class="ml-3 text-sm font-medium">
                                {{ session('success') }}.
                            </div>
                        </div>
                    @endif
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <div class="flex justify-between">
                                    <div>
                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('App Information') }}
                                        </h2>

                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            {{ __("Set the app information.") }}
                                        </p>
                                    </div>
                                </div>
                            </header>

                            <form method="post" action="{{ route('setting.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                <div>
                                    <img class=" h-20 w-20 rounded-full" src="{{ $app ? "/storage/".$app->image : "#" }}" alt="logo"/>
                                </div>

                                <div>
                                    <x-input-label for="image" :value="__('Logo')" />
                                    <x-text-input id="image" name="image" type="file" class="mt-1 block w-full" :value="old('image', $app ? $app->image : 'N/A')" autofocus autocomplete="avatar" />
                                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                                </div>

                                <div>
                                    <x-input-label for="name" :value="__('App Name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $app ? $app->name : 'N/A')" required autofocus autocomplete="name" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="version" :value="__('App Version')" />
                                    <x-text-input id="version" name="version" type="text" class="mt-1 block w-full" :value="old('name', $app ? $app->version : 'N/A')" required autofocus autocomplete="version" />
                                    <x-input-error class="mt-2" :messages="$errors->get('version')" />
                                </div>

                                <div>
                                    <x-input-label for="description" :value="__('Description')" />
                                    <x-text-area-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('name', $app ? $app->description : 'N/A')" required autofocus autocomplete="description" />
                                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('scripts.alert')
</x-app-layout>
