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
        'delete',
        'deleteDevice'
    ];

    protected $rules = [
        'nama_ruang' => 'required',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;
    public $selectedRoomId = null;

    // Ruang properties
    public $dataId, $nama_ruang;

    // Device properties
    public $deviceId;
    public $isEditingDevice = false;
    public $nama_device;
    public $tipe_device = 'onoff';
    public $icon_device;
    public $power_topic;
    public $power_payload_on = 'ON';
    public $power_payload_off = 'OFF';
    public $power_retain = false;
    public $remote_topic;
    public $remote_type;
    public $kwh_enabled = false;
    public $kwh_topic;

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

        // Query ruang dengan device_count (subquery untuk MySQL strict mode)
        $data = DB::table('ruang')
            ->leftJoinSub(
                DB::table('devices')
                    ->select('ruang_id', DB::raw('COUNT(*) as device_count'))
                    ->groupBy('ruang_id'),
                'device_counts',
                'ruang.id',
                '=',
                'device_counts.ruang_id'
            )
            ->select('ruang.*', DB::raw('COALESCE(device_counts.device_count, 0) as device_count'))
            ->where('nama_ruang', 'LIKE', $search)
            ->paginate($this->lengthData);

        // Query devices untuk room yang dipilih
        $devices = [];
        if ($this->selectedRoomId) {
            $devices = DB::table('devices')
                ->where('ruang_id', $this->selectedRoomId)
                ->orderBy('nama_device')
                ->get();
        }

        if ($user->hasRole('admin')) {
            return view('livewire.dashboard.dashboard-admin', compact('data', 'devices'));
        } else if ($user->hasRole('user')) {
            return view('livewire.dashboard.dashboard-user', compact('data', 'devices'));
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

    // ========== DEVICE METHODS ==========

    private function resetDeviceFields()
    {
        $this->deviceId = null;
        $this->nama_device = '';
        $this->tipe_device = 'onoff';
        $this->icon_device = null;
        $this->power_topic = '';
        $this->power_payload_on = 'ON';
        $this->power_payload_off = 'OFF';
        $this->power_retain = false;
        $this->remote_topic = '';
        $this->remote_type = '';
        $this->kwh_enabled = false;
        $this->kwh_topic = '';
    }

    public function cancelDevice()
    {
        $this->isEditingDevice = false;
        $this->resetDeviceFields();
    }

    public function storeDevice()
    {
        $this->validate([
            'nama_device' => 'required',
            'tipe_device' => 'required|in:onoff,remote,cctv',
        ]);

        DB::table('devices')->insert([
            'ruang_id' => $this->selectedRoomId,
            'nama_device' => $this->nama_device,
            'tipe' => $this->tipe_device,
            'icon' => $this->icon_device,
            'power_topic' => $this->power_topic,
            'power_payload_on' => $this->power_payload_on,
            'power_payload_off' => $this->power_payload_off,
            'power_retain' => $this->power_retain,
            'remote_topic' => $this->remote_topic,
            'remote_type' => $this->remote_type,
            'kwh_enabled' => $this->kwh_enabled,
            'kwh_topic' => $this->kwh_topic,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'message' => 'Success!',
            'text' => 'Device created successfully.'
        ]);

        $this->resetDeviceFields();
    }

    public function editDevice($id)
    {
        $this->isEditingDevice = true;
        $device = DB::table('devices')->where('id', $id)->first();
        if (!$device) abort(404);

        $this->deviceId = $id;
        $this->nama_device = $device->nama_device;
        $this->tipe_device = $device->tipe;
        $this->icon_device = $device->icon;
        $this->power_topic = $device->power_topic;
        $this->power_payload_on = $device->power_payload_on;
        $this->power_payload_off = $device->power_payload_off;
        $this->power_retain = $device->power_retain;
        $this->remote_topic = $device->remote_topic;
        $this->remote_type = $device->remote_type;
        $this->kwh_enabled = $device->kwh_enabled;
        $this->kwh_topic = $device->kwh_topic;
    }

    public function updateDevice()
    {
        $this->validate([
            'nama_device' => 'required',
            'tipe_device' => 'required|in:onoff,remote,cctv',
        ]);

        if ($this->deviceId) {
            DB::table('devices')->where('id', $this->deviceId)->update([
                'nama_device' => $this->nama_device,
                'tipe' => $this->tipe_device,
                'icon' => $this->icon_device,
                'power_topic' => $this->power_topic,
                'power_payload_on' => $this->power_payload_on,
                'power_payload_off' => $this->power_payload_off,
                'power_retain' => $this->power_retain,
                'remote_topic' => $this->remote_topic,
                'remote_type' => $this->remote_type,
                'kwh_enabled' => $this->kwh_enabled,
                'kwh_topic' => $this->kwh_topic,
                'updated_at' => now(),
            ]);

            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Success!',
                'text' => 'Device updated successfully.'
            ]);

            $this->isEditingDevice = false;
            $this->resetDeviceFields();
        }
    }

    public function deleteDeviceConfirm($id)
    {
        $this->deviceId = $id;
        $this->dispatch('swal:confirmDevice', [
            'type' => 'warning',
            'message' => 'Hapus device?',
            'text' => 'Device akan dihapus permanen!'
        ]);
    }

    public function deleteDevice()
    {
        DB::table('devices')->where('id', $this->deviceId)->delete();
        $this->dispatch('swal:modal', [
            'type' => 'success',
            'message' => 'Success!',
            'text' => 'Device deleted successfully.'
        ]);
        $this->deviceId = null;
    }
}
