<?php

namespace App\Http\Livewire;

use App\Models\Cuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;


class KelolaCuti extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // public $pegawai;
    public $search;
    public $userId, $dari_tanggal, $sampai_tanggal, $alasan;
    public $isOpen = 0;
    public $updateStateId = 0;

    public function render()
    {
        // $pegawai = User::orderBy('id', 'ASC')->get();
        $searchParam = '%'.$this->search.'%';
        return view('livewire.kelola-cuti', [
            'cuti' => Cuti::where('dari_tanggal', 'like', $searchParam)->latest()->paginate(5)
        ]);
    }

    public function resetInputField()
    {
        $this->userId = '';
        $this->dari_tanggal = '';
        $this->sampai_tanggal = '';
        $this->alasan = '';
    }

    public function showUpdate($id)
    {
        $cuti = Cuti::findOrFail($id);
        // $this->userId = $id;
        $this->dari_tanggal = $cuti->dari_tanggal;
        $this->sampai_tanggal = $cuti->sampai_tanggal;
        $this->alasan = $cuti->alasan;
        // $this->updateStateId = $id;

    }

    public function editCuti($id)
    {
        $cuti = Cuti::where('id', $id)->first();
        $this->userId = $cuti->id;
        $this->dari_tanggal = $cuti->dari_tanggal;
        $this->sampai_tanggal = $cuti->sampai_tanggal;
        $this->alasan = $cuti->alasan;
    }

    public function updateCuti()
    {
        $this->validate(
            [
                'dari_tanggal' => ['required'],
                'sampai_tanggal' => ['required'],
                'alasan' => ['required'],
            ]
        );

        if($this->userId){
            $cuti = Cuti::find($this->userId);
            $cuti->update([
                'dari_tanggal' => $this->dari_tanggal,
                'sampai_tanggal' => $this->sampai_tanggal,
                'alasan' => $this->alasan
            ]);
            $this->resetInputField();
            $this->emit('cutiUpdated');
            session()->flash('message', 'Cuti berhasil diupdate');
        }
    }

    public function deleteCuti($id)
    {
        if($id){
            Cuti::where('id', $id)->delete();
            session()->flash('message', 'Cuti berhasil diarsipkan');
        }
    }

    public function approveCuti($id)
    {
        // $cuti = Cuti::find($id);
        // $cuti->update([
        //     'is_valid' => 1
        // ]);
        $cuti = Cuti::where('id', $id);
        $cuti->update([
            'is_valid' => 1
        ]);
        session()->flash('message', 'Cuti berhasil diapprove');
    }

    public function saveCuti()
    {
        $this->validate(
            [
                'dari_tanggal' => ['required'],
                'sampai_tanggal' => ['required'],
                'alasan' => ['required'],
            ]
        );
        
        Cuti::create([
            'user_id' => Auth::id(),
            'dari_tanggal' => $this->dari_tanggal,
            'sampai_tanggal' => $this->sampai_tanggal,
            'alasan' => $this->alasan,
            'isValid' => 0
        ]);
        $this->resetInputField();
        $this->emit('cutiAdded');
        session()->flash('message', 'Cuti berhasil ditambahkan');
    }
}
