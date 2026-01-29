<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\DB;
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

    public $dataId, $nama_ruang;

    public function mount()
    {
        $firstRoom = DB::table('ruang')->first();
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

        $data = DB::table('ruang')
            ->select('ruang.*', DB::raw('0 as device_count'))
            ->where('nama_ruang', 'LIKE', $search)
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
    }

    public function cancel()
    {
        $this->isEditing = false;
        $this->dataId = null;
        $this->resetInputFields();
    }

    public function store()
    {
        $this->validate();

        DB::table('ruang')->insert([
            'nama_ruang' => $this->nama_ruang,
        ]);

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $data = DB::table('ruang')->where('id', $id)->first();
        if (!$data) abort(404);
        $this->dataId = $id;
        $this->nama_ruang = $data->nama_ruang;
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            DB::table('ruang')->where('id', $this->dataId)->update([
                'nama_ruang' => $this->nama_ruang,
            ]);

            $this->dispatchAlert('success', 'Success!', 'Data updated successfully.');
            $this->isEditing = false;
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
        DB::table('ruang')->where('id', $this->dataId)->delete();
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
