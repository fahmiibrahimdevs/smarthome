<div
    x-data="{ isOn: false, showMenu: false }"
    data-device-type="{{ $device->tipe }}"
    class="tw-rounded-2xl tw-border tw-overflow-hidden tw-transition-all tw-duration-500 tw-ease-out tw-relative"
    :class="isOn
        ? 'bg-primary tw-border-blue-400 tw-shadow-xl tw-shadow-blue-500/30 tw-scale-[1.02]'
        : 'tw-bg-white tw-border-gray-200 tw-shadow-sm hover:tw-shadow-md hover:tw-border-gray-300'"
>
    <div class="tw-p-4 font-bagus">
        <!-- Header: Icon & Power Button -->
        <div class="tw-flex tw-items-start tw-justify-between tw-mb-5">
            <!-- Device Icon -->
            <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-transition-all tw-duration-500" :class="isOn ? 'tw-bg-white/20 tw-backdrop-blur-sm' : 'tw-bg-gray-100'">
                @if ($device->icon)
                    <i class="{{ $device->icon }} tw-text-2xl tw-transition-all tw-duration-500" :class="isOn ? 'tw-text-yellow-300 tw-drop-shadow-[0_0_8px_rgba(253,224,71,0.8)]' : 'tw-text-gray-400'"></i>
                @elseif ($device->tipe === "onoff")
                    <i class="fas fa-lightbulb tw-text-2xl tw-transition-all tw-duration-500" :class="isOn ? 'tw-text-yellow-300 tw-drop-shadow-[0_0_8px_rgba(253,224,71,0.8)]' : 'tw-text-gray-400'"></i>
                @elseif ($device->tipe === "remote")
                    <i class="fas fa-gamepad tw-text-2xl tw-transition-all tw-duration-500" :class="isOn ? 'tw-text-purple-300' : 'tw-text-gray-400'"></i>
                @elseif ($device->tipe === "cctv")
                    <i class="fas fa-video tw-text-2xl tw-transition-all tw-duration-500" :class="isOn ? 'tw-text-red-300' : 'tw-text-gray-400'"></i>
                @endif
            </div>
            <!-- Power/Menu Button -->
            <div class="tw-flex tw-items-center tw-space-x-1">
                @if ($device->tipe === "onoff")
                    <button
                        @click="isOn = !isOn"
                        class="tw-w-10 tw-h-10 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-transition-all tw-duration-300 tw-border-2 active:tw-scale-90"
                        :class="isOn
                            ? 'tw-bg-white tw-border-white tw-text-blue-600 tw-shadow-lg tw-shadow-white/30'
                            : 'tw-bg-gray-100 tw-border-gray-200 tw-text-gray-400 hover:tw-bg-gray-200'"
                    >
                        <i class="fas fa-power-off tw-text-sm"></i>
                    </button>
                @endif

                <button @click="showMenu = !showMenu" @click.away="showMenu = false" class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-transition-colors" :class="isOn ? 'tw-text-white/70 hover:tw-bg-white/10' : 'tw-text-gray-400 hover:tw-bg-gray-100'">
                    <i class="fas fa-ellipsis-v tw-text-xs"></i>
                </button>
                <!-- Dropdown Menu -->
                <div x-show="showMenu" x-transition class="tw-absolute tw-right-2 tw-top-14 tw-bg-white tw-rounded-lg tw-shadow-lg tw-border tw-border-gray-100 tw-py-1 tw-z-10 tw-min-w-[120px]">
                    <button wire:click="editDevice({{ $device->id }})" @click="showMenu = false" data-toggle="modal" data-target="#formDeviceModal" class="tw-w-full tw-text-left tw-px-3 tw-py-2 tw-text-sm tw-text-gray-600 hover:tw-bg-gray-50 tw-flex tw-items-center">
                        <i class="far fa-edit tw-mr-2 tw-text-amber-500"></i>
                        Edit
                    </button>
                    <button wire:click="deleteDeviceConfirm({{ $device->id }})" @click="showMenu = false" class="tw-w-full tw-text-left tw-px-3 tw-py-2 tw-text-sm tw-text-red-500 hover:tw-bg-red-50 tw-flex tw-items-center">
                        <i class="far fa-trash tw-mr-2"></i>
                        Hapus
                    </button>
                </div>
            </div>
        </div>
        <!-- Device Name -->
        <p class="tw-font-semibold tw-m-0 tw-mb-1 tw-truncate tw-transition-colors tw-duration-500" :class="isOn ? 'tw-text-white' : 'tw-text-gray-800'">{{ $device->nama_device }}</p>
        <!-- Status -->
        <div class="tw-flex tw-items-center tw-justify-between">
            <p class="tw-text-sm tw-m-0 tw-transition-colors tw-duration-500" :class="isOn ? 'tw-text-blue-100' : 'tw-text-gray-400'" x-text="isOn ? 'Menyala' : 'Mati'"></p>
            <!-- Indicator dot -->
            <span class="tw-w-2 tw-h-2 tw-rounded-full tw-transition-all tw-duration-500" :class="isOn ? 'tw-bg-green-400 tw-shadow-[0_0_8px_rgba(74,222,128,0.8)] tw-animate-pulse' : 'tw-bg-gray-300'"></span>
        </div>
    </div>
</div>
