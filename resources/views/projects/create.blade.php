<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Project Information') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __("Provide project information to create.") }}
                        </p>
                    </header>

                    <form method="post" action="{{ route('projects.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 mt-6 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="book_title" :value="__('Book Title')" />
                                <x-text-input id="book_title" name="book_title" type="text" class="mt-1 block w-full" :value="old('book_title')" />
                                <x-input-error class="mt-2" :messages="$errors->get('book_title')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="book_description" :value="__('Book Description')" />
                                <x-text-input id="book_description" name="book_description" type="text" class="mt-1 block w-full" :value="old('book_description')" />
                                <x-input-error class="mt-2" :messages="$errors->get('book_description')" />
                            </div>

                            <div>
                                <x-input-label for="book_creator" :value="__('Book Author')" />
                                <x-text-input id="book_creator" name="book_creator" type="text" class="mt-1 block w-full" :value="old('book_creator')" />
                                <x-input-error class="mt-2" :messages="$errors->get('book_creator')" />
                            </div>

                            <div>
                                <x-input-label for="book_subject" :value="__('Book Tag')" />
                                <x-text-input id="book_subject" name="book_subject" type="text" class="mt-1 block w-full" :value="old('book_subject')" />
                                <x-input-error class="mt-2" :messages="$errors->get('book_subject')" />
                            </div>

                            <div>
                                <x-input-label for="book_publisher" :value="__('Book Publisher')" />
                                <x-text-input id="book_publisher" name="book_publisher" type="text" class="mt-1 block w-full" :value="old('book_publisher')" />
                                <x-input-error class="mt-2" :messages="$errors->get('book_publisher')" />
                            </div>

                            <div>
                                <x-input-label for="book_language" :value="__('Book Language')" />
                                <x-select-input id="book_language" name="book_language" class="mt-1 block w-full" :value="old('book_language')" :options="config('languages.all')" />
                                <x-input-error class="mt-2" :messages="$errors->get('book_language')" />
                            </div>

                            <div>
                                <x-input-label for="book_width" :value="__('Book Width')" />
                                <x-text-input id="book_width" name="book_width" type="number" min="0" class="mt-1 block w-full" :value="old('book_width', 1800)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('book_width')" />
                            </div>

                            <div>
                                <x-input-label for="book_height" :value="__('Book Height')" />
                                <x-text-input id="book_height" name="book_height" min="0" type="number" class="mt-1 block w-full" :value="old('book_height', 2700)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('book_height')" />
                            </div>

                            <div>
                                <div class="flex items-center mb-4">
                                    <input @checked(old('book_webtoon_style', false)) id="book_webtoon_style" name="book_webtoon_style" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                    <label for="book_webtoon_style" class="ml-2 text-sm font-medium text-gray-900">{{  __('Book Webtoon Style') }}</label>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('book_webtoon_style')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>

                            @if (session('status') === 'project-created')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600"
                                >{{ __('Saved.') }}</p>
                            @endif
                        </div>

                    </form>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
