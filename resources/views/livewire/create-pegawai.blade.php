<div wire:ignore.self class="modal fade" id="createPegawaiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Data Pegawai</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form>
            <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input wire:model="name" type="text" class="form-control">
                            @error('name') <span class="text-danger">{{$message}}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input wire:model="email" type="email" class="form-control">
                            @error('email') <span class="text-danger">{{$message}}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input wire:model="password" type="password" class="form-control">
                            @error('password') <span class="text-danger">{{$message}}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button wire:click.prevent="savePegawai()" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>