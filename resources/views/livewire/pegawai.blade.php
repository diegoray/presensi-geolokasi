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
                                <h4>Daftar Pegawai</h4>

                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createPegawaiModal">
                                    Tambah Pegawai
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
                                <th scope="col">Email</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            @foreach ($pegawai as $index=>$p)
                            <tr>
                                <th>{{ $index + 1 }}</th>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->email }}</td>
                                <td class="text-center">
                                    <button wire:click.prevent="editPegawai({{ $p->id }})" data-toggle="modal" data-target="#updatePegawaiModal" class="btn btn-warning btn-sm">Edit</button>
                                    <button wire:click.prevent="deletePegawai({{ $p->id }})" class="btn btn-danger btn-sm">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                          </table>
                        </div>
                        <div class="card-footer">
                            <div class="row float-right">
                                {{ $pegawai->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                @include('livewire.create-pegawai')
                @include('livewire.update-pegawai')
                {{-- <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <form>
                        <h3>Kelola Pegawai</h3>
                    </div>
                    <div class="card-body">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input wire:model="userId" type="hidden" class="form-control">
                                <input wire:model="name" type="text" class="form-control">
                                @error('title') <h1 class="text-red-500">{{$message}}</h1>@enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input wire:model="email" type="email" class="form-control">
                                @error('title') <h1 class="text-red-500">{{$message}}</h1>@enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input wire:model="password" type="password" class="form-control">
                                @error('title') <h1 class="text-red-500">{{$message}}</h1>@enderror
                            </div>
                    </div>
                            <div class="card-footer">
                                @if ($updateStateId > 0)
                                
                                <button wire:click="updatePegawai({{  }})" type="button" class="btn btn-warning float-right">Edit</button>
                                 @else
                                 <button wire:click="savePegawai" type="button" class="btn btn-success float-right">Save</button>
                                     
                                 @endif
                            </div>
                        </form>
                    </div>
            </div> --}}
        </div>
    </div>
</div>
