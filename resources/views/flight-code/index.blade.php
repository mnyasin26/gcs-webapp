<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Flight Code') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                    <section>
                        <header>
                            <div class="flex justify-between">
                                <div>
                                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Flight Code Information') }}
                                    </h2>

                                    <p class="mt-1 pb-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __("Set the flight code.") }}
                                    </p>
                                </div>

                                <div>
                                    <form method="post" action="">
                                        @csrf
                                        <x-input-label for="flight_codes" :value="__('Active Flight Code')" />
                                        <select id="flight_codes" class="inline-flex items-center w-56 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#0c0b8b] hover:bg-indigo-800 focus:outline-none transition ease-in-out duration-150">
                                            <option>Select Active Flight Code</option>
                                            @foreach ($flightCode as $code)
                                                @if ($code->id == $selectedFlightCode)
                                                    <option value="{{ $code->id }}" selected>{{ $code->flight_code }}</option>
                                                @else
                                                    <option value="{{ $code->id }}">{{ $code->flight_code }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <x-primary-button class="hidden">{{ __('Select') }}</x-primary-button>
                                    </form>
                                </div>

                                <div>
                                    <x-primary-button-link href="{{ route('flight-code.create') }}">{{ __('Add Flight Code') }}</x-primary-button-link>
                                </div>
                            </div>
                        </header>
                    </section>
                    <table class="table" id="dataFlightCode">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Flight Code</th>
                                <th>Action</th>
                            </tr>
                        <tbody class="text-center"></tbody>
                        </thead>
                    </table>

                    {{-- <form action="" method="POST" id="deleteForm">
                        @csrf
                        @method("DELETE")
                        <input type="submit" value="Delete" style="display: none">
                    </form> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- Datatables -->
    <link rel="stylesheet" type="text/css"  href="https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.print.min.js"></script>

    <script>
    // ajaxserver side  datatable untuk menampilkan data centrepoint
        $(function() {
            $('#dataFlightCode').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'A3',
                        text: 'Export to PDF',
                        title: '{{ $app ? $app->name : "GCS" }}_TelemetriLogs',
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        title: '{{ $app ? $app->name : "GCS"}}_TelemetriLogs'
                    }
                ],
                ajax: '{{ route('flight-code.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'flight_code'
                    },
                    {
                        data: 'action'
                    }
                ]
            });

            setTimeout(function() {
                $('#alert-info').fadeOut(500);
            }, 5000); // <-- time in milliseconds

            $('#flight_codes').on('change', function(e){
                var select = $(this), form = select.closest('form');
                form.attr('action', 'flight-code/' + select.val() + '/select');
                form.submit();
            });
        })
    </script>
</x-app-layout>
