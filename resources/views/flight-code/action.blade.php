{{-- pada view action terdapat 2 button edit data dan hapus data --}}
<x-primary-button-link href="{{ route('flight-code.edit',$id) }}">{{ __('Edit') }}</x-primary-button-link>
<x-danger-button
    x-data=""
    x-on:click.prevent="$dispatch('open-modal', 'confirm-flight-code-deletion')"
>{{ __('Delete') }}</x-danger-button>

<x-modal name="confirm-flight-code-deletion" focusable>
    <form method="post" action="{{ route('flight-code.destroy', $id) }}" class="p-6">
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Are you sure you want to delete this flight code?') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your data is deleted, all of its resources and data will be permanently deleted.') }}
        </p>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-3" autofocus>
                {{ __('Delete') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>
