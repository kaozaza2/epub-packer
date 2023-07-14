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
                                loading: false,
                                async upload(files) {
                                    let chunk = [];
                                    let previousSize = 0;
                                    for (let i = 0; i < files.length; i++) {
                                        const isLastFile = i === files.length - 1;
                                        if (previousSize > 10000000 || isLastFile) {
                                            if (isLastFile) {
                                                chunk.push(files[i]);
                                            }
                                            const form = new FormData();
                                            for (let j = 0; j < chunk.length; j++) {
                                                form.append('images[]', chunk[j]);
                                            }
                                            await window.axios.post('{{ route('attachments.add', ['project' => $project]) }}', form)
                                                .then(r => isLastFile && window.location.reload())
                                                .catch(e => {
                                                    window.Swal.fire({
                                                        icon: 'error',
                                                        title: 'Oops...',
                                                        text: e.response.data.message,
                                                    })
                                                    .then(() => {
                                                        window.location.reload();
                                                    });
                                                });
                                            chunk = [];
                                            previousSize = 0;
                                            continue;
                                        }
                                        chunk.push(files[i]);
                                        previousSize += files[i].size;
                                    }
                                },
                            }"
                            class="flex gap-x-1 items-center"
                        >
                            <div x-cloak x-show="loading" role="status">
                                <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                </svg>
                                <span class="sr-only">Loading...</span>
                            </div>

                            <input x-ref="file" @change="loading = true; upload($event.target.files)" type="file" class="hidden" multiple>

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
