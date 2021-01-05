<?php

namespace App\Http\Livewire;

use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Location;
use App\Models\Test;
use Illuminate\Support\Facades\Storage;

class KelolaPresensi extends Component
{
    public function render()
    {
        return view('livewire.kelola-presensi');
    }
}
