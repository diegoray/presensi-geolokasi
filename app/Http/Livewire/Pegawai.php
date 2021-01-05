<?php

namespace App\Http\Livewire;

use App\Models\Test;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;


class Pegawai extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // public $pegawai;
    public $search;
    public $userId, $name, $email, $password;
    public $isOpen = 0;
    public $updateStateId = 0;

    public function render()
    {
        // $pegawai = User::orderBy('id', 'ASC')->get();
        $searchParam = '%'.$this->search.'%';
        return view('livewire.pegawai', [
            'pegawai' => User::where('name', 'like', $searchParam)->latest()->paginate(5)
        ]);
    }

    public function resetInputField()
    {
        // $this->userId = '';
        $this->name = '';
        $this->email = '';
        $this->password = '';
    }

    public function showUpdate($id)
    {
        $user = User::findOrFail($id);
        // $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        // $this->updateStateId = $id;

    }

    public function editPegawai($id)
    {
        $user = User::where('id', $id)->first();
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updatePegawai()
    {
        $this->validate(
            [
                'name' => ['required'],
                'email' => ['required', 'email'],
            ]
        );

        if($this->userId){
            $user = User::find($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
            $this->resetInputField();
            $this->emit('pegawaiUpdated');
            session()->flash('message', 'Pegawai berhasil diupdate');
        }
    }

    public function deletePegawai($id)
    {
        if($id){
            User::where('id', $id)->delete();
            session()->flash('message', 'Pegawai berhasil diarsipkan');
        }
    }

    public function savePegawai()
    {
        $this->validate(
            [
                'name' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]
        );
        
        $p = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password)
        ]);
        $p->assignRole('pegawai');
        $this->resetInputField();
        $this->emit('pegawaiAdded');
        session()->flash('message', 'Pegawai berhasil ditambahkan');
        return $p;

        // User::create([
        //     'name' => $this->name,
        //     'email' => $this->email,
        //     'password' => Hash::make($this->password)
        // ]);
        // User::updateOrCreate(['id' => $this->userId], [
        //     'name' => $this->name,
        //     'email' => $this->email,
        //     'password' => Hash::make($this->password)
        // ]);
        // $p = User::updateOrCreate(['id' => $this->userId], [
        //     'name' => $this->name,
        //     'email' => $this->email,
        //     'password' => Hash::make($this->password)
        // ]);

        // session()->flash('info', $this->userId ? 'Post Update Successfully' : 'Post Created Successfully' );

        // dd($p);
    }

    // public function edit($id){
    //     $post = Post::findOrFail($id);
    //     $this->postId = $id;
    //     $this->title = $post->title;
    //     $this->description = $post->description;

    //     $this->showModal();
    // }

    // public function delete($id){
    //     Post::find($id)->delete();
    //     session()->flash('delete','Post Successfully Deleted');
    // }


}
