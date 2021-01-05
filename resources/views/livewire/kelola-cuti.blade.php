<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if (session()->has('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10 d-flex justify-content-between align-items-center">
                                <h4>Daftar Cuti</h4>

                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCutiModal">
                                    Tambah Cuti
                                </button>
                            </div>
                            <div class="col-md-2">
                                <input wire:model="search" type="text" class="form-control" placeholder="Cari..." aria-label="Username" aria-describedby="basic-addon1">

                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Dari Tanggal</th>
                                    <th scope="col">Sampai Tanggal</th>
                                    <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            @foreach ($cuti as $index=>$c)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $c->user->name }}</td>
                                <td>{{ $c->dari_tanggal }}</td>
                                <td>{{ $c->sampai_tanggal }}</td>
                                <td class="text-center">
                                    <button wire:click.prevent="editCuti({{ $c->id }})" data-toggle="modal" data-target="#updateCutiModal" class="btn btn-warning btn-sm">Edit</button>
                                    <button wire:click.prevent="deleteCuti({{ $c->id }})" class="btn btn-danger btn-sm">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                          </table>
                        </div>
                        <div class="card-footer">
                            <div class="row float-right">
                                {{ $cuti->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                @include('livewire.create-cuti')
                @include('livewire.update-cuti')
        </div>
    </div>
</div>
