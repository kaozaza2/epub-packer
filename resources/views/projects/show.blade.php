<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-x-2">
            <a href="{{ route('projects.index') }}"
               class="font-semibold text-xl text-gray-400 leading-tight rounded hover:text-gray-500">
                {{ __('Project') }}
            </a>

            <h2 class="font-semibold text-xl text-gray-400 leading-tight">
                /
            </h2>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>

                    @if(session('status') !== null)
                        <div
                            x-data="{
                                init() {
                                    setTimeout(() =>
                                        window.Swal.fire({
                                            icon: 'success',
                                            title: '{{ __('Success') }}',
                                            text: '{{ session('status') }}',
                                            showConfirmButton: false,
                                            timer: 1500
                                        })
                                    , 100);
                                },
                            }"
                            x-init="init()"
                        ></div>
                    @endif

                    @if($errors->any())
                        <div
                            x-data="{
                                init() {
                                    setTimeout(() =>
                                        window.Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: '{{ implode(', ', $errors->all()) }}',
                                            showConfirmButton: false,
                                            timer: 1500
                                        })
                                    , 100);
                                },
                            }"
                            x-init="init()"
                        ></div>
                    @endif


                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ $project->name }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ $project->book_title }}
                        </p>
                    </header>

                    <div class="grid grid-cols-1 md:grid-cols-2 mt-6 gap-2">
                        <div>
                            <span class="text-gray-700 font-medium">{{  __('Id') }}: </span>
                            <span class="text-gray-600">@dash($project->book_id)</span>
                        </div>

                        <div>
                            <span class="text-gray-700 font-medium">{{  __('Title') }}: </span>
                            <span class="text-gray-600">@dash($project->book_title)</span>
                        </div>

                        <div>
                            <span class="text-gray-700 font-medium">{{  __('Description') }}: </span>
                            <span class="text-gray-600">@dash($project->book_title)</span>
                        </div>

                        <div>
                            <span class="text-gray-700 font-medium">{{  __('Author') }}: </span>
                            <span class="text-gray-600">@dash($project->book_author)</span>
                        </div>

                        <div>
                            <span class="text-gray-700 font-medium">{{  __('Tag') }}: </span>
                            <span class="text-gray-600">@dash($project->book_subject)</span>
                        </div>

                        <div>
                            <span class="text-gray-700 font-medium">{{  __('Publisher') }}: </span>
                            <span class="text-gray-600">@dash($project->book_publisher)</span>
                        </div>

                        <div>
                            <span class="text-gray-700 font-medium">{{  __('Language') }}: </span>
                            <span class="text-gray-600">
                                {{ $project->book_language != null ? config('languages.all.'.$project->book_language) : '-' }}
                            </span>
                        </div>

                        <div class="flex flex-col md:flex-row">
                            <div class="md:w-1/2">
                                <span class="text-gray-700 font-medium">{{  __('Width') }}: </span>
                                <span class="text-gray-600">{{ $project->book_width }} px</span>
                            </div>

                            <div class="md:w-1/2">
                                <span class="text-gray-700 font-medium">{{  __('Height') }}: </span>
                                <span class="text-gray-600">{{ $project->book_height }} px</span>
                            </div>

                            <div class="md:w-1/2">
                                <span class="text-gray-700 font-medium">{{  __('Webtoon') }}: </span>
                                <span class="text-gray-600">{{ $project->book_webtoon_style ? 'yes' : 'no' }}</span>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200 my-2"/>

                    <div class="flex items-center gap-1 md:justify-end">

                        @if($project->books->count() > 0)
                            <form method="post" action="{{ route('books.delete', ['project' => $project]) }}">
                                @csrf
                                @method('DELETE')
                                <x-primary-button class="bg-red-600">
                                    {{ __('Clear EPUB') }}
                                </x-primary-button>
                            </form>

                            <div
                            x-data="{
                                 open: false,
                                 toggle() {
                                     if (this.open) {
                                         return this.close();
                                     }
                                     this.$refs.button.focus();
                                     this.open = true;
                                 },
                                 close(focusAfter) {
                                     if (! this.open) return;
                                     this.open = false;
                                     focusAfter && focusAfter.focus();
                                 }
                            }"
                            x-on:keydown.escape.prevent.stop="close($refs.button)"
                            x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                            x-id="['dropdown-button']"
                            class="relative">
                                <button
                                    x-ref="button"
                                    x-on:click="toggle()"
                                    :aria-expanded="open"
                                    :aria-controls="$id('dropdown-button')"
                                    type="button"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    type="button"
                                >
                                    Download EPUB
                                    <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                    </svg>
                                </button>

                                <div
                                    x-ref="panel"
                                    x-show="open"
                                    x-transition.origin.top.left
                                    x-on:click.outside="close($refs.button)"
                                    :id="$id('dropdown-button')"
                                    style="display: none;"
                                    class="absolute left-0 mt-2 w-40 rounded-md bg-white shadow-md"
                                >
                                    <ul class="py-2 text-sm text-gray-700">
                                        @foreach($project->books as $book)
                                            <li>
                                                <a href="{{ route('books.download', ['project' => $project, 'book' => $book]) }}" class="block px-4 py-2 hover:bg-gray-100">
                                                    {{ $book->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif


                        <div
                            x-data="{
                                upload(files) {
                                    const form = new FormData();
                                    for (let i = 0; i < files.length; i++) {
                                        form.append('images[]', files[i]);
                                    }

                                    window.axios.post('{{ route('attachments.add', ['project' => $project]) }}', form)
                                        .then((response) => {
                                            window.location.reload();
                                        })
                                        .catch((error) => {
                                            window.Swal.fire({
                                                icon: 'error',
                                                title: 'Oops...',
                                                text: error.response.data.message,
                                            });
                                        });
                                },
                            }"
                        >
                            <input x-ref="file" @change="upload($event.target.files)" type="file" class="hidden" multiple>

                            <x-primary-button type="button" class="bg-green-600" type="button" @click="$refs.file.click()">
                                {{ __('Add image(s)') }}
                            </x-primary-button>
                        </div>

                        @if($project->attachments->count() > 0)
                            <form method="post" action="{{ route('attachments.epub', ['project' => $project]) }}">
                                @csrf
                                <x-primary-button>Create EPUB</x-primary-button>
                            </form>
                        @endif
                    </div>

                    @if ($project->attachments->count() > 0)
                        <hr class="border-gray-200 my-2"/>

                        @include('projects.sort', ['project' => $project])
                    @endif

                </section>
            </div>
        </div>
    </div>
</x-app-layout>
