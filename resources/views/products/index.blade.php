@extends('templates.layoutApp')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.6.1/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
@endpush

@section('content')
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row">
                    <!--begin::Col-->

                    <!--end::Col-->

                    <!--end::Col-->

                    <!--end::Col-->
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <!--begin::Row-->
                <div class="row">

                    <!-- Products List -->
                    <div class="col-12">
                        <div class="card mb-4">

                            <div class="card-header">
                                <h3 class="card-title">Products List</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#createProductModal">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        PRODUCT
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="d-flex gap-2 mb-3">
                                    <button id="export-csv" type="button" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-filetype-csv me-1"></i>
                                        Export CSV
                                    </button>

                                    <button id="export-json" type="button" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-filetype-json me-1"></i>
                                        Export JSON
                                    </button>

                                    <button id="print-table" type="button" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-printer me-1"></i>
                                        Print
                                    </button>
                                </div>

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                <div id="product-table" aria-label="Products"></div>

                                <div id="table-pagination" class="d-flex flex-wrap gap-2 justify-content-end mt-2">
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /.row (main row) -->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
        @endsection

        @section('modal')
            @include('products.modal')
        @endsection

        @push('script')
            <script src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.6.1/dist/js/tabulator.min.js" crossorigin="anonymous">
            </script>

            <script>
                const statusBadge = (cell) => {
                    const value = cell.getValue();
                    const map = {
                        Active: 'success',
                        Invited: 'info',
                        Suspended: 'secondary'
                    };
                    const color = map[value] || 'secondary';
                    return `<span class="badge text-bg-${color}">${value}</span>`;
                };

                document.addEventListener('DOMContentLoaded', () => {

                    // Buka kembali modal jika validasi gagal, supaya pesan error terlihat
                    @if ($errors->any())
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('createProductModal')).show();
                    @endif

                    const table = new Tabulator('#product-table', {
                        ajaxURL: "{{ route('products.api') }}", // Mengambil data langsung dari Route JSON Laravel
                        layout: 'fitColumns',
                        filterMode: "local",
                        pagination: "local",
                        paginationElement: document.getElementById('table-pagination'),
                        paginationSize: 10,
                        paginationSizeSelector: [10, 25, 50, 100],
                        movableColumns: true,
                        columns: [{
                                title: 'ID',
                                field: 'id',
                                width: 80,
                                headerSort: true
                            },
                            {
                                title: 'Product Name',
                                field: 'name',
                            },
                            {
                                title: 'Price',
                                field: 'price',
                            },
                            {
                                title: 'Stock',
                                field: 'stock',
                                width: 120,
                            },
                            {
                                title: 'Aksi',
                                field: 'id',
                                formatter: function(cell) {
                                    var id = cell.getValue();
                                    var editUrl = "{{ url('products') }}/" + id + "/edit";

                                    // Membuat tombol Edit dan Delete manual di dalam Tabulator
                                    return `
                            <a href="${editUrl}" class="btn btn-success">Edit</a>
                            <button onclick="deleteProduct(${id})" class="btn btn-danger">Hapus</button>
                        `;
                                },
                                headerSort: false,
                                borderless: true,
                                width: 200,
                                hozAlign: 'center',
                            },
                        ],
                    });

                    table.on('tableBuilt', () => {
                        // Keep the scroll region keyboard reachable inside a valid grid.
                        // Pagination lives outside the grid so its buttons are not grid rows.
                        const grid = document.getElementById('product-table');
                        grid.removeAttribute('aria-owns');
                        grid.querySelector('.tabulator-tableholder').setAttribute('role', 'rowgroup');
                        grid.querySelector('.tabulator-table').setAttribute('role', 'presentation');
                    });

                    table.on('renderComplete', () => {
                        document.querySelectorAll('#table-pagination button').forEach((button) => {
                            button.classList.add('btn', 'btn-sm', 'btn-outline-secondary');
                        });
                    });

                    document.getElementById('table-filter')?.addEventListener('input', (e) => {
                        const value = e.target.value;
                        if (value) {
                            table.setFilter(
                                [{
                                    field: 'name',
                                    type: 'like',
                                    value: value
                                }]
                            );
                        } else {
                            table.clearFilter();
                        }
                    });

                    document
                        .getElementById('export-csv')
                        .addEventListener('click', () => table.download('csv', 'users.csv'));
                    document
                        .getElementById('export-json')
                        .addEventListener('click', () => table.download('json', 'users.json'));
                    document
                        .getElementById('print-table')
                        .addEventListener('click', () => table.print(false, true));
                });
            </script>
        @endpush
