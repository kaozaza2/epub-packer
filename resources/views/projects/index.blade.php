<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex justify-end">
                <a href="/projects/create" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-green-500 focus:z-10 focus:ring-4 focus:ring-gray-200">
                    {{ __('Create') }}
                </a>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @forelse($projects as $project)
                        <div @class(['flex items-center justify-between p-4 border border-gray-200 rounded', 'md:col-span-2' => $loop->last && $loop->iteration % 2 != 0])>
                            <p>{{ $project->name }}</p>

                            <div class="flex justify-end gap-x-1">
                                <x-link-button :href="route('projects.show', ['project' => $project])" class="bg-green-600">
                                    {{ __ ('Show') }}
                                </x-link-button>

                                <x-link-button :href="route('projects.edit', ['project' => $project])">
                                    {{ __ ('Edit') }}
                                </x-link-button>

                                <form action="{{ route('projects.destroy', ['project' => $project]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <x-primary-button type="submit" class="bg-red-600">
                                        {{ __ ('Delete') }}
                                    </x-primary-button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 p-4 border border-gray-200 rounded">
                            <p class="text-center">{{ __('No projects found.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
