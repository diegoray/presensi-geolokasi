<div wire:ignore.self class="modal fade" id="createCutiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Data Cuti</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form>
            <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label for="dari_tanggal">Dari Tanggal</label>
                            <input wire:model="dari_tanggal" type="date" class="form-control">
                            @error('dari_tanggal') <span class="text-danger">{{$message}}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="sampai_tanggal">sampai_tanggal</label>
                            <input wire:model="sampai_tanggal" type="date" class="form-control">
                            @error('sampai_tanggal') <span class="text-danger">{{$message}}</span>@enderror
                        </div>
                        <div class="form-group">
                          <label for="alasan">Alasan</label>
                          <input wire:model="alasan" type="text" class="form-control">
                          @error('alasan') <span class="text-danger">{{$message}}</span>@enderror
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button wire:click.prevent="saveCuti()" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>