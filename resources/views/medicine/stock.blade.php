@extends('layouts.template')

@section('content')
    <div id="msg-success"></div>

    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Stok</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($medicines as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td style="{{ $item['stock'] <= 3 ? 'background: red; color: white' : 'background: none; color: black' }}">{{ $item['stock'] }}</td>
                    <td class="d-flex justify-content-center">
                        <div onclick="edit({{ $item['id'] }})" class="btn btn-primary me-3" style="cursor: pointer">Tambah Stock</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        @if ($medicines->count())
            {{ $medicines->links() }}
        @endif
    </div>

    <div class="modal" tabindex="-1" id="tambah-stock">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Data Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                </div>
                <form method="POST" id="form-stock">
                    <div class="modal-body">
                        <div id="msg"></div>
                        {{-- Input hidden tidak akan tertampil, biasanya digunakan untuk menyimpan data yang diperlukan diproses BE tapi tidak boleh diketahui / diubah user --}}
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Obat :</label>
                            <input type="text" class="form-control" id="name" name="name" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock Obat :</label>
                            <input type="number" class="form-control" id="stock" name="stock">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">

        // csrf token versi js
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content'),
        //     }
        // });

        // function edit(id) {
        //     // Panggil route dari web.php yang akan menangani proses ambil satu data
        //     let url = "{{ route('medicine.show', 'id') }}";
        //     // Ganti bagian 'id' di url nya jadi data dari parameter id di function nya
        //     url = url.replace('id', id);
        //     // Pengambilan data dari FE ke BE dijembatani oleh jquery ajax
        //     $.ajax({
        //         // Route nya pake method :: apa
        //         type: 'GET',
        //         // Link route nya dari let url
        //         url: url,
        //         // Data yang dihasilin bentuknya json
        //         contentType: 'json',
        //         // Kalau proses ambil data berhasil, ambil data yang dikirim BE lewat parameternya res
        //         success: function (res) {
        //             // Munculkan modal yang id nya tambah-stock
        //             $('#tambah-stock').modal('show');
        //             // Isi value input dari hasil response BE
        //             $('#name').val(res.name);
        //             $('#stock').val(res.stock);
        //             $('#id').val(res.id);
        //         }  
        //     });
        // }

        // // Ketika form dengan id="form-stack" button submit nya di klik 
        // $('#form-stock').submit(function(e) {
        //     // Element form penanganan action nya akan diambil alih (ditangani) oleh js
        //     e.preventDefault();
        //     // Ambil value dari inputan id yang disembunyikan, untuk mengisi path {id} di routenya
        //     let id = $("#id").val();
        //     // Route action penanganan update data
        //     let url = "{{ route('medicine.stock.update', 'id') }}";
        //     url = url.replace('id', id);
        //     // Buat variable data yang akan dikirim ke BE
        //     let data = {
        //         stock: $("#stock").val()
        //     }

        //     $.ajax({
        //         type: 'PATCH',
        //         url: url,
        //         data: data,
        //         cache: false,
        //         success: function () {
        //             // Jika berhasil, modal di hide
        //             $("#tambah-stock").modal('hide');
        //             // Buat session js bernama 'successUpdateStock'
        //             sessionStorage.reloadAfterPageLoad = true;
        //             window.location.reload();
        //         },
        //         error: function (err) {
        //             // Kalau terjadi error, paad element id="msg" tambah class dengan value alert alert-danger
        //             $('#msg').attr("class", "alert alert-danger");
        //             // Isi text element id="msg" diambil dari response json bagian message
        //             $('#msg').text(data.responseJSON.message);
        //         }
        //     });
        // });

        // $(function () {
        //     if (sessionStorage.successUpdateStock) {
        //         $('#msg-success').attr("class", "alert alert-success");
        //         $('#msg-success').text("Berhasil menambahkan data stock");
        //         // Hapus kembali data session setelah alert success dimunculkan
        //         sessionStorage.clear();
        //     }
        // });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });

        function edit(id) {
            let url = "{{ route('medicine.stock.edit', ":id") }}";
            url = url.replace(':id', id);
            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(res) {
                    $('#tambah-stock').modal('show');
                    $('#id').val(res.id);
                    $('#name').val(res.name);
                    $('#stock').val(res.stock);
                }
            });

            $('#form-stock').submit(function(e) {
                e.preventDefault();

                var id = $('#id').val();
                var urlForm = "{{ route('medicine.stock.update', ":id") }}";
                urlForm = urlForm.replace(':id', id);

                var data = {
                    stock: $('#stock').val(),
                }

                $.ajax({
                    type: 'PATCH',
                    url: urlForm,
                    data: data,
                    cache: false,
                    success: (data) => {
                        $("#tambah-stock").modal('hide');
                        sessionStorage.reloadAfterPageLoad = true;
                        window.location.reload();
                    },
                    error: function(data) {
                        $('#msg').attr("class", "alert alert-danger");
                        $('#msg').text(data.responseJSON.message);
                    }
                });
            });

            $(function () {
                if (sessionStorage.reloadAfterPageLoad) {
                    $('#msg-success').attr("class", "alert alert-success");
                    $('#msg-success').text("Berhasil mengubah data stock");
                    sessionStorage.clear();
                }
            })
        } 
    </script>
@endpush

