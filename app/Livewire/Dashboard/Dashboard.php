<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use App\Models\Ruang;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;
    #[Title('Dashboard')]
    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'nama_ruang' => 'required',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;
    public $selectedRoomId = null;

    public $dataId, $nama_ruang, $deskripsi;

    public function mount()
    {
        $firstRoom = Ruang::first();
        if ($firstRoom) {
            $this->selectedRoomId = $firstRoom->id;
        }
    }

    public function selectRoom($id)
    {
        $this->selectedRoomId = $id;
    }

    public function render()
    {
        $user = User::find(Auth::user()->id);

        $this->searchResetPage();
        $search = '%' . $this->searchTerm . '%';

        $data = Ruang::where('nama_ruang', 'LIKE', $search)
            ->paginate($this->lengthData);

        if ($user->hasRole('admin')) {
            return view('livewire.dashboard.dashboard-admin', compact('data'));
        } else if ($user->hasRole('user')) {
            return view('livewire.dashboard.dashboard-user', compact('data'));
        }
    }

    private function dispatchAlert($type, $message, $text)
    {
        $this->dispatch('swal:modal', [
            'type'      => $type,
            'message'   => $message,
            'text'      => $text
        ]);

        $this->resetInputFields();
    }

    public function isEditingMode($mode)
    {
        $this->isEditing = $mode;
    }

    private function resetInputFields()
    {
        $this->nama_ruang = '';
        $this->deskripsi = '';
    }

    public function cancel()
    {
        $this->resetInputFields();
    }

    public function store()
    {
        $this->validate();

        Ruang::create([
            'nama_ruang'     => $this->nama_ruang,
            'deskripsi'      => $this->deskripsi,
        ]);

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $data = Ruang::findOrFail($id);
        $this->dataId = $id;
        $this->nama_ruang  = $data->nama_ruang;
        $this->deskripsi  = $data->deskripsi;
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            Ruang::findOrFail($this->dataId)->update([
                'nama_ruang' => $this->nama_ruang,
                'deskripsi' => $this->deskripsi,
            ]);

            $this->dispatchAlert('success', 'Success!', 'Data updated successfully.');
            $this->dataId = null;
        }
    }

    public function deleteConfirm($id)
    {
        $this->dataId = $id;
        $this->dispatch('swal:confirm', [
            'type'      => 'warning',
            'message'   => 'Are you sure?',
            'text'      => 'If you delete the data, it cannot be restored!'
        ]);
    }

    public function delete()
    {
        Ruang::findOrFail($this->dataId)->delete();
        $this->dispatchAlert('success', 'Success!', 'Data deleted successfully.');
    }

    public function updatingLengthData()
    {
        $this->resetPage();
    }

    private function searchResetPage()
    {
        if ($this->searchTerm !== $this->previousSearchTerm) {
            $this->resetPage();
        }

        $this->previousSearchTerm = $this->searchTerm;
    }
}
