<div
    x-data="{
        selected: [],
        sendSortOrder() {
            window.axios.put('{{ route('attachments.sort', ['project' => $project]) }}', {
                order: this.sortOrder,
            }).then(response => {
                window.location.reload();
            });
        },
        toggleSelect(id) {
            if (this.selected.includes(id)) {
                this.selected = this.selected.filter(item => item !== id);
            } else {
                this.selected.push(id);
            }
        },
        sendDeleteSelected() {
            window.axios.delete('{{ route('attachments.destroy', ['project' => $project]) }}', {
                data: { delete: this.selected },
            }).then(response => {
                window.location.reload();
            });
        },
        ...laravelBladeSortable()
    }"
    data-name
    x-ref="root"
    x-init="name = ''; animation = 150; ghostClass = ''; allowSort = true; allowDrop = true; init()"
    class="flex flex-col gap-2"
>
    @foreach ($project->attachments->sortBy('order_num') as $attachment)
        <div
            draggable="true"
            class="flex flex-col p-2 border border-gray-200 rounded-lg"
            data-sort-key="{{ $attachment->id }}">
            <div class="flex items-center gap-1">
                <input
                    @change="toggleSelect({{ $attachment->id }})"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                    type="checkbox"
                    name="deletion[]"
                    multiple
                >
                <span class="text-gray-600">{{ $attachment->filename }}</span>
            </div>
        </div>
    @endforeach

    <div class="flex items-center justify-end mt-2 gap-1">
        <x-primary-button
            @click="sendDeleteSelected()"
            type="button" class="bg-red-500"
        >
            {{ __('Delete') }}
        </x-primary-button>

        <x-primary-button
            @click="sendSortOrder()"
            type="button" class="bg-green-500"
        >
            {{ __('Save') }}
        </x-primary-button>

        <x-link-button x-data @click="window.location.reload();" type="button" class="hover:bg-red-500">
            {{ __('Reset') }}
        </x-link-button>
    </div>
</div>
