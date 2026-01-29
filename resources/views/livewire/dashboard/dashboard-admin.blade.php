<div x-data="mqttConnection()" x-init="init()" x-cloak>
    <!-- MQTT Connection Modal -->
    <template x-if="showModal">
        <div class="tw-fixed tw-inset-0 tw-z-[9999] tw-flex tw-items-center tw-justify-center tw-bg-gray-900/60 tw-backdrop-blur-sm tw-p-4">
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-2xl tw-w-full tw-max-w-sm tw-overflow-hidden">
                <!-- Header -->
                <div class="tw-bg-gradient-to-br tw-from-blue-500 tw-to-indigo-600 tw-px-5 tw-py-4">
                    <div class="tw-flex tw-items-center tw-space-x-3">
                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                            <i class="fas fa-wifi tw-text-white"></i>
                        </div>
                        <div>
                            <h3 class="tw-text-white tw-font-semibold tw-m-0">Koneksi MQTT</h3>
                            <p class="tw-text-blue-100 tw-text-xs tw-m-0 tw-opacity-80">Broker WebSocket</p>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="tw-p-5 tw-space-y-4">
                    <!-- Hostname & Port Row -->
                    <div class="tw-grid tw-grid-cols-3 tw-gap-3">
                        <div class="tw-col-span-2">
                            <label class="tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wide">Host</label>
                            <input type="text" x-model="mqtt.hostname" placeholder="192.168.1.1" class="tw-mt-1 tw-w-full tw-px-3 tw-py-2 tw-bg-gray-50 tw-border tw-border-gray-200 tw-rounded-lg tw-text-sm tw-text-gray-800 placeholder:tw-text-gray-400 focus:tw-bg-white focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100 tw-outline-none tw-transition-all" />
                        </div>
                        <div>
                            <label class="tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wide">Port</label>
                            <input type="number" x-model="mqtt.port" placeholder="9001" class="tw-mt-1 tw-w-full tw-px-3 tw-py-2 tw-bg-gray-50 tw-border tw-border-gray-200 tw-rounded-lg tw-text-sm tw-text-gray-800 placeholder:tw-text-gray-400 focus:tw-bg-white focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100 tw-outline-none tw-transition-all" />
                        </div>
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wide">Username</label>
                        <input type="text" x-model="mqtt.username" placeholder="optional" class="tw-mt-1 tw-w-full tw-px-3 tw-py-2 tw-bg-gray-50 tw-border tw-border-gray-200 tw-rounded-lg tw-text-sm tw-text-gray-800 placeholder:tw-text-gray-400 focus:tw-bg-white focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100 tw-outline-none tw-transition-all" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wide">Password</label>
                        <div class="tw-relative tw-mt-1">
                            <input :type="showPassword ? 'text' : 'password'" x-model="mqtt.password" placeholder="optional" class="tw-w-full tw-px-3 tw-py-2 tw-pr-10 tw-bg-gray-50 tw-border tw-border-gray-200 tw-rounded-lg tw-text-sm tw-text-gray-800 placeholder:tw-text-gray-400 focus:tw-bg-white focus:tw-border-blue-400 focus:tw-ring-2 focus:tw-ring-blue-100 tw-outline-none tw-transition-all" />
                            <button type="button" @click="showPassword = !showPassword" class="tw-absolute tw-right-3 tw-top-1/2 -tw-translate-y-1/2 tw-text-gray-400 hover:tw-text-gray-600 tw-transition-colors">
                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="tw-text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <label class="tw-flex tw-items-center tw-space-x-2 tw-cursor-pointer tw-select-none">
                        <input type="checkbox" x-model="saveCredentials" class="tw-w-4 tw-h-4 tw-rounded tw-border-gray-300 tw-text-blue-500 focus:tw-ring-blue-400" />
                        <span class="tw-text-sm tw-text-gray-600">Ingat kredensial</span>
                    </label>

                    <!-- Error Message -->
                    <template x-if="connectionStatus === 'error'">
                        <div class="tw-flex tw-items-center tw-space-x-2 tw-text-red-600 tw-bg-red-50 tw-px-3 tw-py-2 tw-rounded-lg tw-text-sm">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span x-text="errorMessage"></span>
                        </div>
                    </template>
                </div>

                <!-- Footer -->
                <div class="tw-px-5 tw-py-4 tw-bg-gray-50 tw-border-t tw-border-gray-100 tw-flex tw-items-center tw-justify-between">
                    <button @click="clearSavedCredentials()" class="tw-text-xs tw-text-gray-400 hover:tw-text-red-500 tw-transition-colors">
                        <i class="fas fa-eraser tw-mr-1"></i>
                        Reset
                    </button>
                    <button @click="connect()" :disabled="connectionStatus === 'connecting'" class="tw-px-5 tw-py-2 tw-bg-gradient-to-r tw-from-blue-500 tw-to-indigo-600 tw-text-white tw-font-medium tw-rounded-lg tw-text-sm hover:tw-from-blue-600 hover:tw-to-indigo-700 tw-transition-all tw-shadow-md hover:tw-shadow-lg disabled:tw-opacity-60 disabled:tw-cursor-wait tw-flex tw-items-center tw-space-x-2">
                        <template x-if="connectionStatus === 'connecting'">
                            <i class="fas fa-circle-notch tw-animate-spin"></i>
                        </template>
                        <template x-if="connectionStatus === 'connected'">
                            <i class="fas fa-check"></i>
                        </template>
                        <template x-if="connectionStatus !== 'connecting' && connectionStatus !== 'connected'">
                            <i class="fas fa-plug"></i>
                        </template>
                        <span x-text="
                            connectionStatus === 'connecting'
                                ? 'Connecting...'
                                : connectionStatus === 'connected'
                                  ? 'Connected!'
                                  : 'Connect'
                        "></span>
                    </button>
                </div>
            </div>
        </div>
    </template>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>

        <div class="section-body">
            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-x-0 lg:tw-gap-x-8">
                <div class="tw-col-span-1">
                    <div class="tw-flex tw-space-x-4 tw-overflow-x-auto no-scrollbar tw-pb-2 lg:tw-grid lg:tw-grid-cols-1 lg:tw-gap-x-4 lg:tw-space-x-0 lg:tw-overflow-visible tw-px-4 lg:tw-px-0 tw-pt-4 lg:tw-pt-0">
                        <div class="card tw-rounded-lg tw-shadow-md tw-shadow-gray-200 tw-border tw-border-gray-100 tw-overflow-hidden tw-min-w-[280px] tw-flex-shrink-0 lg:tw-min-w-0 lg:tw-flex-shrink">
                            <div class="card-body tw-px-4 font-bagus tw-relative tw-bg-white">
                                <!-- Decorative -->
                                <div class="tw-absolute tw-top-0 tw-right-0 tw-w-20 tw-h-20 tw-bg-indigo-100/50 tw-rounded-full -tw-mr-6 -tw-mt-6"></div>

                                <!-- Header -->
                                <div class="tw-flex tw-items-center tw-space-x-2 tw-mb-4 tw-relative tw-z-10">
                                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-indigo-500/20 tw-flex tw-items-center tw-justify-center">
                                        <i class="fas fa-bolt tw-text-indigo-600 tw-text-sm"></i>
                                    </div>
                                    <h3 class="tw-font-semibold tw-text-base tw-m-0 tw-text-gray-700">Pemakaian kWh</h3>
                                </div>

                                <!-- Stats -->
                                <div class="tw-grid tw-grid-cols-2 tw-gap-3 tw-relative tw-z-10">
                                    <div class="tw-bg-white tw-rounded-xl tw-p-3 tw-border tw-border-gray-100 tw-shadow-sm">
                                        <div class="tw-flex tw-items-center tw-justify-between tw-mb-1">
                                            <span class="tw-text-gray-400 tw-text-xs">Hari Ini</span>
                                            <i class="fas fa-bolt tw-text-yellow-400 tw-text-xs"></i>
                                        </div>
                                        <p class="tw-text-xl tw-font-bold tw-text-gray-700 tw-m-0">
                                            42.1
                                            <span class="tw-text-sm tw-font-normal tw-text-gray-400">kWh</span>
                                        </p>
                                    </div>
                                    <div class="tw-bg-white tw-rounded-xl tw-p-3 tw-border tw-border-gray-100 tw-shadow-sm">
                                        <div class="tw-flex tw-items-center tw-justify-between tw-mb-1">
                                            <span class="tw-text-gray-400 tw-text-xs">Bulan Ini</span>
                                            <i class="fas fa-calendar tw-text-indigo-400 tw-text-xs"></i>
                                        </div>
                                        <p class="tw-text-xl tw-font-bold tw-text-gray-700 tw-m-0">
                                            342.16
                                            <span class="tw-text-sm tw-font-normal tw-text-gray-400">kWh</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card tw-rounded-lg tw-shadow-md tw-shadow-gray-200 tw-border tw-border-gray-100 tw-overflow-hidden tw-mt-0 tw-min-w-[280px] tw-flex-shrink-0 lg:tw-min-w-0 lg:tw-flex-shrink">
                            <div class="card-body tw-px-4 font-bagus tw-relative tw-bg-white">
                                <!-- Decorative -->
                                <div class="tw-absolute tw-top-0 tw-right-0 tw-w-20 tw-h-20 tw-bg-red-100/50 tw-rounded-full -tw-mr-6 -tw-mt-6"></div>

                                <!-- Header -->
                                <div class="tw-flex tw-items-center tw-space-x-2 tw-mb-4 tw-relative tw-z-10">
                                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-red-500/20 tw-flex tw-items-center tw-justify-center">
                                        <i class="fas fa-wallet tw-text-red-600 tw-text-sm"></i>
                                    </div>
                                    <h3 class="tw-font-semibold tw-text-base tw-m-0 tw-text-gray-700">Estimasi Biaya</h3>
                                </div>

                                <!-- Amount -->
                                <div class="tw-bg-white tw-rounded-xl tw-p-4 tw-border tw-border-gray-100 tw-shadow-sm tw-relative tw-z-10">
                                    <p class="tw-text-2xl tw-font-bold tw-text-gray-700 tw-m-0">Rp 512.400</p>
                                    <p class="tw-text-xs tw-text-gray-400 tw-m-0 tw-mt-1">Bulan ini (per kWh Rp 1.500)</p>
                                </div>
                            </div>
                        </div>
                        <div class="card tw-rounded-lg tw-shadow-md tw-shadow-gray-200 tw-mt-0 tw-overflow-hidden tw-border tw-border-gray-100 tw-min-w-[280px] tw-flex-shrink-0 lg:tw-min-w-0 lg:tw-flex-shrink">
                            <div class="card-body tw-px-4 font-bagus tw-relative tw-bg-white">
                                <!-- Decorative -->
                                <div class="tw-absolute tw-top-0 tw-right-0 tw-w-24 tw-h-24 tw-bg-teal-100/50 tw-rounded-full -tw-mr-8 -tw-mt-8"></div>
                                <div class="tw-absolute tw-bottom-0 tw-left-0 tw-w-16 tw-h-16 tw-bg-cyan-100/50 tw-rounded-full -tw-ml-6 -tw-mb-6"></div>

                                <!-- Header -->
                                <div class="tw-flex tw-items-center tw-justify-between tw-mb-4 tw-relative tw-z-10">
                                    <div class="tw-flex tw-items-center tw-space-x-2">
                                        <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-teal-500/20 tw-flex tw-items-center tw-justify-center">
                                            <i class="fas fa-microchip tw-text-teal-600 tw-text-sm"></i>
                                        </div>
                                        <div>
                                            <h3 class="tw-font-semibold tw-text-base tw-m-0 tw-text-gray-700">Suhu Ruangan</h3>
                                            <p class="tw-text-xs tw-text-gray-400 tw-m-0">R. Teras</p>
                                        </div>
                                    </div>
                                    <div class="tw-flex tw-items-center tw-space-x-1 tw-bg-green-100 tw-rounded-full tw-px-2 tw-py-1">
                                        <span class="tw-w-2 tw-h-2 tw-bg-green-500 tw-rounded-full tw-animate-pulse"></span>
                                        <span class="tw-text-xs tw-text-green-600 tw-font-medium">Online</span>
                                    </div>
                                </div>

                                <!-- Sensor Data -->
                                <div class="tw-grid tw-grid-cols-2 tw-gap-3 tw-relative tw-z-10">
                                    <!-- Temperature -->
                                    <div class="tw-bg-white tw-rounded-xl tw-p-3 tw-border tw-border-gray-100 tw-shadow-sm">
                                        <div class="tw-flex tw-items-center tw-justify-between tw-mb-2">
                                            <span class="tw-text-gray-400 tw-text-xs">Suhu</span>
                                            <i class="fas fa-temperature-high tw-text-orange-400 tw-text-sm"></i>
                                        </div>
                                        <p class="tw-text-2xl tw-font-bold tw-text-gray-700 tw-m-0">
                                            28.5
                                            <span class="tw-text-sm tw-font-normal tw-text-gray-400">°C</span>
                                        </p>
                                    </div>

                                    <!-- Humidity -->
                                    <div class="tw-bg-white tw-rounded-xl tw-p-3 tw-border tw-border-gray-100 tw-shadow-sm">
                                        <div class="tw-flex tw-items-center tw-justify-between tw-mb-2">
                                            <span class="tw-text-gray-400 tw-text-xs">Kelembaban</span>
                                            <i class="fas fa-droplet tw-text-cyan-400 tw-text-sm"></i>
                                        </div>
                                        <p class="tw-text-2xl tw-font-bold tw-text-gray-700 tw-m-0">
                                            65
                                            <span class="tw-text-sm tw-font-normal tw-text-gray-400">%</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tw-col-span-2">
                    <div class="tw-flex tw-space-x-2.5 tw-w-auto no-scrollbar tw-overflow-x-auto tw-whitespace-nowrap tw-px-4 lg:tw-px-0 tw-pb-2">
                        <button class="btn btn-transparent tw-border-2 tw-border-gray-300 tw-border-dashed font-bagus tw-text-gray-500 tw-px-4 tw-py-1.5 tw-rounded-xl" data-toggle="modal" data-target="#formRuanganModal">
                            <i class="fas fa-plus mx-2"></i>
                            <span class="tw-mr-2">Kelola Ruangan</span>
                        </button>
                        @foreach ($data as $item)
                            <button wire:click="selectRoom({{ $item->id }})" class="tw-group btn tw-px-5 tw-py-3 tw-rounded-xl tw-shadow-sm hover:tw-shadow-md tw-transition-all tw-duration-200 tw-flex tw-items-center tw-border {{ $selectedRoomId == $item->id ? "btn-primary tw-text-white" : "tw-bg-white hover:tw-bg-gray-50 tw-text-gray-500 tw-border-gray-100 hover:tw-border-blue-200" }}">
                                <div class="tw-mr-3 tw-flex tw-items-center tw-justify-center tw-transition-colors {{ $selectedRoomId == $item->id ? "tw-text-white" : "tw-text-blue-500 group-hover:tw-text-blue-600" }}">
                                    <i class="far fa-home tw-text-2xl"></i>
                                </div>
                                <div class="tw-text-left">
                                    <p class="font-bagus tw-text-sm tw-m-0 tw-leading-tight tw-transition-colors {{ $selectedRoomId == $item->id ? "tw-font-bold tw-text-white" : "tw-text-gray-800 tw-font-normal group-hover:tw-text-blue-700" }}">
                                        {{ $item->nama_ruang }}
                                    </p>
                                    <p class="tw-text-xs tw-m-0 tw-mt-0.5 tw-font-normal {{ $selectedRoomId == $item->id ? "tw-text-blue-100" : "tw-text-gray-400" }}">2 Perangkat</p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                    <div class="tw-px-4 lg:tw-px-0 tw-mt-6 font-bagus">
                        <!-- Nav Pills Device Types (Alpine.js) -->
                        <div class="tw-mt-6" x-data="{ activeTab: 'power' }" x-init="
                            $watch('activeTab', (value) => localStorage.setItem('deviceActiveTab', value))
                            activeTab = localStorage.getItem('deviceActiveTab') || 'power'
                        ">
                            <!-- Tab Headers -->
                            <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                                <ul class="nav nav-pills" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button @click="activeTab = 'power'" class="nav-link" :class="activeTab === 'power' ? 'active' : ''" type="button">
                                            <i class="fas fa-power-off mr-1"></i>
                                            Power
                                            <span class="badge badge-light ml-1">{{ collect($devices)->where("tipe", "onoff")->count() }}</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button @click="activeTab = 'remote'" class="nav-link" :class="activeTab === 'remote' ? 'active' : ''" type="button">
                                            <i class="fas fa-gamepad mr-1"></i>
                                            Remote
                                            <span class="badge badge-light ml-1">{{ collect($devices)->where("tipe", "remote")->count() }}</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button @click="activeTab = 'cctv'" class="nav-link" :class="activeTab === 'cctv' ? 'active' : ''" type="button">
                                            <i class="fas fa-video mr-1"></i>
                                            CCTV
                                            <span class="badge badge-light ml-1">{{ collect($devices)->where("tipe", "cctv")->count() }}</span>
                                        </button>
                                    </li>
                                </ul>

                                <!-- Master Toggle for Power -->
                                @if (collect($devices)->where("tipe", "onoff")->count() > 0)
                                    <div x-data="{ allOn: false }" id="masterToggle">
                                        <button
                                            @click="allOn = !allOn; document.querySelectorAll('[data-device-type=onoff]').forEach(el => { el.__x.$data.isOn = allOn })"
                                            class="tw-w-10 tw-h-10 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-transition-all tw-duration-300 tw-border-2 active:tw-scale-90"
                                            :class="allOn
                                                ? 'tw-bg-green-500 tw-border-green-500 tw-text-white tw-shadow-lg tw-shadow-green-500/30'
                                                : 'tw-bg-gray-100 tw-border-gray-200 tw-text-gray-400 hover:tw-bg-gray-200'"
                                            :title="allOn ? 'Matikan Semua' : 'Nyalakan Semua'"
                                        >
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- Tab Contents -->
                            <div>
                                <!-- POWER Tab -->
                                <div x-show="activeTab === 'power'" x-transition>
                                    <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-4">
                                        @forelse (collect($devices)->where("tipe", "onoff") as $device)
                                            @include("livewire.dashboard.partials.device-card", ["device" => $device])
                                        @empty
                                            <div class="tw-col-span-2 lg:tw-col-span-3 tw-text-center tw-py-12">
                                                <div class="tw-w-16 tw-h-16 tw-mx-auto tw-rounded-full tw-bg-gray-100 tw-flex tw-items-center tw-justify-center tw-mb-3">
                                                    <i class="fas fa-power-off tw-text-2xl tw-text-gray-300"></i>
                                                </div>
                                                <p class="tw-text-gray-500 tw-m-0">Belum ada perangkat Power</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- REMOTE Tab -->
                                <div x-show="activeTab === 'remote'" x-transition>
                                    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-4">
                                        @forelse (collect($devices)->where("tipe", "remote") as $device)
                                            @if ($device->remote_type === "ac")
                                                <!-- AC Remote Card -->
                                                <div class="card tw-rounded-xl tw-shadow-sm tw-shadow-gray-200 tw-border tw-border-gray-100" x-data="{ isOn: false, temp: 24, mode: 'cool' }">
                                                    <div class="card-body tw-p-5 font-bagus tw-bg-white tw-rounded-xl">
                                                        <!-- Header -->
                                                        <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                                                            <div class="tw-flex tw-items-center tw-space-x-3">
                                                                <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-gray-100 tw-flex tw-items-center tw-justify-center">
                                                                    <i class="{{ $device->icon ?: "fas fa-wind" }} tw-text-gray-500"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="tw-font-semibold tw-text-gray-800 tw-m-0">{{ $device->nama_device }}</p>
                                                                    <p class="tw-text-xs tw-text-gray-400 tw-m-0">Remote AC</p>
                                                                </div>
                                                            </div>
                                                            <div class="tw-flex tw-items-center tw-gap-2">
                                                                <label class="tw-relative tw-inline-flex tw-items-center tw-cursor-pointer">
                                                                    <input type="checkbox" class="tw-sr-only tw-peer" x-model="isOn" />
                                                                    <div class="tw-w-11 tw-h-6 tw-bg-gray-200 peer-focus:tw-outline-none tw-rounded-full tw-peer peer-checked:after:tw-translate-x-full peer-checked:after:tw-border-white after:tw-content-[''] after:tw-absolute after:tw-top-[2px] after:tw-left-[2px] after:tw-bg-white after:tw-border-gray-300 after:tw-border after:tw-rounded-full after:tw-h-5 after:tw-w-5 after:tw-transition-all peer-checked:tw-bg-blue-500"></div>
                                                                </label>
                                                                <!-- Menu Button -->
                                                                <div x-data="{ showMenu: false }" class="tw-relative">
                                                                    <button @click="showMenu = !showMenu" class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-text-gray-400 hover:tw-bg-gray-100">
                                                                        <i class="fas fa-ellipsis-v tw-text-xs"></i>
                                                                    </button>
                                                                    <div x-show="showMenu" @click.away="showMenu = false" x-transition class="tw-absolute tw-right-0 tw-top-8 tw-bg-white tw-rounded-lg tw-shadow-lg tw-border tw-border-gray-100 tw-py-1 tw-z-10 tw-min-w-[120px]">
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
                                                        </div>

                                                        <!-- Temperature Display -->
                                                        <div class="tw-text-center tw-py-6 tw-bg-gray-50 tw-rounded-xl tw-mb-4">
                                                            <p class="tw-text-5xl tw-font-bold tw-m-0 tw-transition-colors" :class="isOn ? 'tw-text-gray-800' : 'tw-text-gray-300'" x-text="temp + '°C'"></p>
                                                            <p class="tw-text-sm tw-text-gray-400 tw-m-0 tw-mt-2">Suhu</p>
                                                        </div>

                                                        <!-- Temperature Controls -->
                                                        <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                                                            <button @click="temp > 16 ? temp-- : null" :disabled="!isOn" class="tw-w-12 tw-h-12 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-text-xl tw-font-bold tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40 disabled:tw-cursor-not-allowed" :class="isOn ? 'tw-text-gray-600' : 'tw-text-gray-300'">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <div class="tw-flex tw-items-center tw-space-x-1 tw-text-gray-400 tw-text-xs">
                                                                <span>16°C</span>
                                                                <div class="tw-w-24 tw-h-1 tw-bg-gray-200 tw-rounded-full tw-mx-2">
                                                                    <div class="tw-h-full tw-bg-blue-400 tw-rounded-full tw-transition-all" :style="'width: ' + ((temp - 16) / 14 * 100) + '%'"></div>
                                                                </div>
                                                                <span>30°C</span>
                                                            </div>
                                                            <button @click="temp < 30 ? temp++ : null" :disabled="!isOn" class="tw-w-12 tw-h-12 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-text-xl tw-font-bold tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40 disabled:tw-cursor-not-allowed" :class="isOn ? 'tw-text-gray-600' : 'tw-text-gray-300'">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>

                                                        <!-- Mode Buttons -->
                                                        <div class="tw-grid tw-grid-cols-4 tw-gap-2">
                                                            <button @click="mode = 'cool'" :disabled="!isOn" class="tw-flex tw-flex-col tw-items-center tw-p-3 tw-rounded-xl tw-border tw-transition-all tw-duration-200" :class="mode === 'cool' && isOn ? 'tw-bg-blue-50 tw-border-blue-200 tw-text-blue-500' : 'tw-bg-white tw-border-gray-100 tw-text-gray-400'">
                                                                <i class="fas fa-snowflake tw-text-lg tw-mb-1"></i>
                                                                <span class="tw-text-[10px]">Cool</span>
                                                            </button>
                                                            <button @click="mode = 'fan'" :disabled="!isOn" class="tw-flex tw-flex-col tw-items-center tw-p-3 tw-rounded-xl tw-border tw-transition-all tw-duration-200" :class="mode === 'fan' && isOn ? 'tw-bg-blue-50 tw-border-blue-200 tw-text-blue-500' : 'tw-bg-white tw-border-gray-100 tw-text-gray-400'">
                                                                <i class="fas fa-fan tw-text-lg tw-mb-1"></i>
                                                                <span class="tw-text-[10px]">Fan</span>
                                                            </button>
                                                            <button @click="mode = 'dry'" :disabled="!isOn" class="tw-flex tw-flex-col tw-items-center tw-p-3 tw-rounded-xl tw-border tw-transition-all tw-duration-200" :class="mode === 'dry' && isOn ? 'tw-bg-blue-50 tw-border-blue-200 tw-text-blue-500' : 'tw-bg-white tw-border-gray-100 tw-text-gray-400'">
                                                                <i class="fas fa-droplet tw-text-lg tw-mb-1"></i>
                                                                <span class="tw-text-[10px]">Dry</span>
                                                            </button>
                                                            <button @click="mode = 'auto'" :disabled="!isOn" class="tw-flex tw-flex-col tw-items-center tw-p-3 tw-rounded-xl tw-border tw-transition-all tw-duration-200" :class="mode === 'auto' && isOn ? 'tw-bg-blue-50 tw-border-blue-200 tw-text-blue-500' : 'tw-bg-white tw-border-gray-100 tw-text-gray-400'">
                                                                <i class="fas fa-rotate tw-text-lg tw-mb-1"></i>
                                                                <span class="tw-text-[10px]">Auto</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($device->remote_type === "tv")
                                                <!-- TV Remote Card -->
                                                <div class="card tw-rounded-xl tw-shadow-sm tw-shadow-gray-200 tw-border tw-border-gray-100" x-data="{ isOn: false, volume: 25, channel: 5 }">
                                                    <div class="card-body tw-p-5 font-bagus tw-bg-white tw-rounded-xl">
                                                        <!-- Header -->
                                                        <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                                                            <div class="tw-flex tw-items-center tw-space-x-3">
                                                                <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-gray-100 tw-flex tw-items-center tw-justify-center">
                                                                    <i class="{{ $device->icon ?: "fas fa-tv" }} tw-text-gray-500"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="tw-font-semibold tw-text-gray-800 tw-m-0">{{ $device->nama_device }}</p>
                                                                    <p class="tw-text-xs tw-text-gray-400 tw-m-0">Remote TV</p>
                                                                </div>
                                                            </div>
                                                            <div class="tw-flex tw-items-center tw-gap-2">
                                                                <label class="tw-relative tw-inline-flex tw-items-center tw-cursor-pointer">
                                                                    <input type="checkbox" class="tw-sr-only tw-peer" x-model="isOn" />
                                                                    <div class="tw-w-11 tw-h-6 tw-bg-gray-200 peer-focus:tw-outline-none tw-rounded-full tw-peer peer-checked:after:tw-translate-x-full peer-checked:after:tw-border-white after:tw-content-[''] after:tw-absolute after:tw-top-[2px] after:tw-left-[2px] after:tw-bg-white after:tw-border-gray-300 after:tw-border after:tw-rounded-full after:tw-h-5 after:tw-w-5 after:tw-transition-all peer-checked:tw-bg-blue-500"></div>
                                                                </label>
                                                                <!-- Menu Button -->
                                                                <div x-data="{ showMenu: false }" class="tw-relative">
                                                                    <button @click="showMenu = !showMenu" class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-text-gray-400 hover:tw-bg-gray-100">
                                                                        <i class="fas fa-ellipsis-v tw-text-xs"></i>
                                                                    </button>
                                                                    <div x-show="showMenu" @click.away="showMenu = false" x-transition class="tw-absolute tw-right-0 tw-top-8 tw-bg-white tw-rounded-lg tw-shadow-lg tw-border tw-border-gray-100 tw-py-1 tw-z-10 tw-min-w-[120px]">
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
                                                        </div>

                                                        <!-- Volume & Channel Controls -->
                                                        <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-mb-4">
                                                            <!-- Volume -->
                                                            <div class="tw-bg-gray-50 tw-rounded-xl tw-p-3">
                                                                <p class="tw-text-xs tw-text-gray-400 tw-m-0 tw-mb-2 tw-text-center">Volume</p>
                                                                <div class="tw-flex tw-items-center tw-justify-between">
                                                                    <button @click="volume > 0 ? volume-- : null" :disabled="!isOn" class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40" :class="isOn ? 'tw-text-gray-600' : 'tw-text-gray-300'">
                                                                        <i class="fas fa-volume-down tw-text-xs"></i>
                                                                    </button>
                                                                    <span class="tw-text-xl tw-font-bold" :class="isOn ? 'tw-text-gray-800' : 'tw-text-gray-300'" x-text="volume"></span>
                                                                    <button @click="volume < 100 ? volume++ : null" :disabled="!isOn" class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40" :class="isOn ? 'tw-text-gray-600' : 'tw-text-gray-300'">
                                                                        <i class="fas fa-volume-up tw-text-xs"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <!-- Channel -->
                                                            <div class="tw-bg-gray-50 tw-rounded-xl tw-p-3">
                                                                <p class="tw-text-xs tw-text-gray-400 tw-m-0 tw-mb-2 tw-text-center">Channel</p>
                                                                <div class="tw-flex tw-items-center tw-justify-between">
                                                                    <button @click="channel > 1 ? channel-- : null" :disabled="!isOn" class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40" :class="isOn ? 'tw-text-gray-600' : 'tw-text-gray-300'">
                                                                        <i class="fas fa-chevron-down tw-text-xs"></i>
                                                                    </button>
                                                                    <span class="tw-text-xl tw-font-bold" :class="isOn ? 'tw-text-gray-800' : 'tw-text-gray-300'" x-text="channel"></span>
                                                                    <button @click="channel < 999 ? channel++ : null" :disabled="!isOn" class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40" :class="isOn ? 'tw-text-gray-600' : 'tw-text-gray-300'">
                                                                        <i class="fas fa-chevron-up tw-text-xs"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Quick Actions -->
                                                        <p class="tw-text-xs tw-text-gray-400 tw-m-0 tw-mb-2 tw-mt-4">Navigasi</p>
                                                        <div class="tw-flex tw-items-center tw-justify-center tw-gap-3">
                                                            <!-- Quick Buttons Left -->
                                                            <div class="tw-flex tw-flex-col tw-gap-2">
                                                                <button :disabled="!isOn" class="tw-w-10 tw-h-10 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40" :class="isOn ? 'tw-text-gray-600' : 'tw-text-gray-300'">
                                                                    <i class="fas fa-home tw-text-sm"></i>
                                                                </button>
                                                                <button :disabled="!isOn" class="tw-w-10 tw-h-10 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40" :class="isOn ? 'tw-text-gray-600' : 'tw-text-gray-300'">
                                                                    <i class="fas fa-arrow-left tw-text-sm"></i>
                                                                </button>
                                                            </div>

                                                            <!-- D-Pad -->
                                                            <div class="tw-grid tw-grid-cols-3 tw-gap-1">
                                                                <div></div>
                                                                <button :disabled="!isOn" class="tw-w-10 tw-h-10 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40" :class="isOn ? 'tw-text-gray-600' : 'tw-text-gray-300'">
                                                                    <i class="fas fa-chevron-up tw-text-sm"></i>
                                                                </button>
                                                                <div></div>
                                                                <button :disabled="!isOn" class="tw-w-10 tw-h-10 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40" :class="isOn ? 'tw-text-gray-600' : 'tw-text-gray-300'">
                                                                    <i class="fas fa-chevron-left tw-text-sm"></i>
                                                                </button>
                                                                <button :disabled="!isOn" class="tw-w-10 tw-h-10 tw-rounded-xl tw-border tw-border-blue-200 tw-bg-blue-50 tw-flex tw-items-center tw-justify-center tw-transition-all hover:tw-bg-blue-100 disabled:tw-opacity-40" :class="isOn ? 'tw-text-blue-500' : 'tw-text-gray-300'">
                                                                    <span class="tw-text-[9px] tw-font-bold">OK</span>
                                                                </button>
                                                                <button :disabled="!isOn" class="tw-w-10 tw-h-10 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40" :class="isOn ? 'tw-text-gray-600' : 'tw-text-gray-300'">
                                                                    <i class="fas fa-chevron-right tw-text-sm"></i>
                                                                </button>
                                                                <div></div>
                                                                <button :disabled="!isOn" class="tw-w-10 tw-h-10 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40" :class="isOn ? 'tw-text-gray-600' : 'tw-text-gray-300'">
                                                                    <i class="fas fa-chevron-down tw-text-sm"></i>
                                                                </button>
                                                                <div></div>
                                                            </div>

                                                            <!-- Quick Buttons Right -->
                                                            <div class="tw-flex tw-flex-col tw-gap-2">
                                                                <button :disabled="!isOn" class="tw-w-10 tw-h-10 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40" :class="isOn ? 'tw-text-gray-600' : 'tw-text-gray-300'">
                                                                    <i class="fas fa-bars tw-text-sm"></i>
                                                                </button>
                                                                <button :disabled="!isOn" class="tw-w-10 tw-h-10 tw-rounded-xl tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-transition-all hover:tw-bg-gray-50 disabled:tw-opacity-40" :class="isOn ? 'tw-text-red-500' : 'tw-text-gray-300'">
                                                                    <i class="fas fa-volume-xmark tw-text-sm"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <!-- Generic Remote Card (for other types) -->
                                                @include("livewire.dashboard.partials.device-card", ["device" => $device])
                                            @endif
                                        @empty
                                            <div class="tw-col-span-1 lg:tw-col-span-2 tw-text-center tw-py-12">
                                                <div class="tw-w-16 tw-h-16 tw-mx-auto tw-rounded-full tw-bg-gray-100 tw-flex tw-items-center tw-justify-center tw-mb-3">
                                                    <i class="fas fa-gamepad tw-text-2xl tw-text-gray-300"></i>
                                                </div>
                                                <p class="tw-text-gray-500 tw-m-0">Belum ada perangkat Remote</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- CCTV Tab -->
                                <div x-show="activeTab === 'cctv'" x-transition>
                                    <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-4">
                                        @forelse (collect($devices)->where("tipe", "cctv") as $device)
                                            @include("livewire.dashboard.partials.device-card", ["device" => $device])
                                        @empty
                                            <div class="tw-col-span-2 lg:tw-col-span-3 tw-text-center tw-py-12">
                                                <div class="tw-w-16 tw-h-16 tw-mx-auto tw-rounded-full tw-bg-gray-100 tw-flex tw-items-center tw-justify-center tw-mb-3">
                                                    <i class="fas fa-video tw-text-2xl tw-text-gray-300"></i>
                                                </div>
                                                <p class="tw-text-gray-500 tw-m-0">Belum ada CCTV</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button wire:click.prevent="isEditingMode(false)" class="btn-modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#formDeviceModal">
                <i class="far fa-plus"></i>
            </button>
        </div>
    </section>

    <div class="modal fade" wire:ignore.self id="formRuanganModal" aria-labelledby="formRuanganModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6 tw-border-b-0">
                    <h5 class="modal-title tw-font-semibold" id="formRuanganModalLabel">
                        <i class="far fa-door-open tw-mr-2 tw-text-blue-500"></i>
                        Kelola Ruangan
                    </h5>
                    <button type="button" wire:click="cancel()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body tw-px-4 lg:tw-px-6 tw-pt-0">
                    <!-- Nav Pills Tabs -->
                    <ul class="nav nav-pills tw-my-3 tw-bg-gray-100 tw-p-1 tw-rounded-lg" id="ruanganTabs" role="tablist" wire:ignore.self>
                        <li class="nav-item tw-flex-1">
                            <a class="nav-link tw-text-center tw-rounded-md {{ ! $isEditing ? "active" : "" }}" id="list-tab" data-toggle="pill" href="#listRuangan" role="tab">
                                <i class="far fa-list tw-mr-1"></i>
                                Daftar Ruangan
                            </a>
                        </li>
                        <li class="nav-item tw-flex-1">
                            <a class="nav-link tw-text-center tw-rounded-md {{ $isEditing ? "active" : "" }}" id="form-tab" data-toggle="pill" href="#formRuangan" role="tab">
                                <i class="far fa-{{ $isEditing ? "edit" : "plus" }} tw-mr-1"></i>
                                {{ $isEditing ? "Edit Ruangan" : "Tambah Ruangan" }}
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Contents -->
                    <div class="tab-content" id="ruanganTabsContent" wire:ignore.self>
                        <!-- List Tab -->
                        <div class="tab-pane fade {{ ! $isEditing ? "show active" : "" }}" id="listRuangan" role="tabpanel">
                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-3 tw-max-h-auto tw-overflow-y-auto tw-pr-1">
                                @forelse ($data as $item)
                                    <div class="tw-bg-white tw-border tw-border-gray-200 tw-rounded-xl tw-p-4 tw-shadow-sm hover:tw-shadow-md tw-transition-all tw-duration-200">
                                        <div class="tw-flex tw-items-start tw-justify-between">
                                            <div class="tw-flex tw-items-center tw-space-x-3">
                                                <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-blue-100 tw-flex tw-items-center tw-justify-center">
                                                    <i class="far fa-home tw-text-blue-500"></i>
                                                </div>
                                                <div>
                                                    <p class="tw-font-semibold tw-text-gray-800 tw-m-0">{{ $item->nama_ruang }}</p>
                                                    <p class="tw-text-xs tw-text-gray-400 tw-m-0 tw-mt-0.5">
                                                        <i class="far fa-microchip tw-mr-1"></i>
                                                        {{ $item->device_count ?? 0 }} Perangkat
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="tw-flex tw-items-center tw-space-x-1">
                                                <button wire:click="edit({{ $item->id }})" class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-amber-50 tw-text-amber-500 tw-flex tw-items-center tw-justify-center hover:tw-bg-amber-100 tw-transition-colors tw-border-0">
                                                    <i class="far fa-edit tw-text-sm"></i>
                                                </button>
                                                <button wire:click="deleteConfirm({{ $item->id }})" class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-red-50 tw-text-red-500 tw-flex tw-items-center tw-justify-center hover:tw-bg-red-100 tw-transition-colors tw-border-0">
                                                    <i class="far fa-trash tw-text-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="tw-col-span-2 tw-text-center tw-py-8">
                                        <div class="tw-w-16 tw-h-16 tw-mx-auto tw-rounded-full tw-bg-gray-100 tw-flex tw-items-center tw-justify-center tw-mb-3">
                                            <i class="far fa-inbox tw-text-2xl tw-text-gray-400"></i>
                                        </div>
                                        <p class="tw-text-gray-500 tw-m-0">Belum ada ruangan</p>
                                        <p class="tw-text-xs tw-text-gray-400 tw-m-0">Klik tab "Tambah Ruangan" untuk menambahkan</p>
                                    </div>
                                @endforelse
                            </div>
                            @if ($data->hasPages())
                                <div class="tw-mt-4 tw-pt-3 tw-border-t tw-border-gray-100">
                                    {{ $data->links() }}
                                </div>
                            @endif
                        </div>

                        <!-- Form Tab -->
                        <div class="tab-pane fade {{ $isEditing ? "show active" : "" }}" id="formRuangan" role="tabpanel">
                            <form>
                                <div class="tw-bg-gray-50 tw-rounded-xl tw-p-4">
                                    <div class="form-group tw-mb-0">
                                        <label for="nama_ruang" class="tw-text-sm tw-font-medium tw-text-gray-700">
                                            <i class="far fa-tag tw-mr-1 tw-text-blue-500"></i>
                                            Nama Ruangan
                                        </label>
                                        <input type="text" wire:model="nama_ruang" id="nama_ruang" class="form-control tw-rounded-lg tw-border-gray-200 focus:tw-border-blue-400 focus:tw-ring focus:tw-ring-blue-200 focus:tw-ring-opacity-50" placeholder="Contoh: Ruang Tamu, Kamar Tidur..." />
                                        @error("nama_ruang")
                                            <small class="tw-text-red-500">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="tw-flex tw-justify-end tw-space-x-2 tw-mt-4">
                                    @if ($isEditing)
                                        <button type="button" wire:click="cancel()" class="btn tw-bg-gray-100 tw-text-gray-600 tw-rounded-lg tw-px-4 hover:tw-bg-gray-200 tw-transition-colors">
                                            <i class="far fa-times tw-mr-1"></i>
                                            Batal
                                        </button>
                                    @endif

                                    <button type="submit" wire:click.prevent="{{ $isEditing ? "update()" : "store()" }}" wire:loading.attr="disabled" class="btn btn-primary tw-bg-blue-500 tw-rounded-lg tw-px-4 hover:tw-bg-blue-600 tw-transition-colors">
                                        <i class="far fa-{{ $isEditing ? "save" : "plus" }} tw-mr-1"></i>
                                        <span wire:loading.remove>{{ $isEditing ? "Simpan Perubahan" : "Tambah Ruangan" }}</span>
                                        <span wire:loading>Menyimpan...</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" wire:ignore.self id="formDeviceModal" aria-labelledby="formDeviceModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header">
                    <h5 class="modal-title" id="formDeviceModalLabel">
                        {{ $isEditingDevice ? "Edit Device" : "Tambah Device" }}
                    </h5>
                    <button type="button" wire:click="cancelDevice()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <!-- Ruang -->
                        <div class="form-group" wire:ignore>
                            <label>Ruang</label>
                            <select id="selectRuang" class="form-control" style="width: 100%">
                                @foreach ($data as $ruang)
                                    <option value="{{ $ruang->id }}" {{ $selectedRoomId == $ruang->id ? "selected" : "" }}>{{ $ruang->nama_ruang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nama Device -->
                        <div class="form-group">
                            <label for="nama_device">Nama Device</label>
                            <input type="text" wire:model="nama_device" id="nama_device" class="form-control" placeholder="Contoh: Lampu Ruang Tamu..." />
                            @error("nama_device")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Tipe Device -->
                        <div class="form-group">
                            <label>Tipe Device</label>
                            <select wire:model.live="tipe_device" class="form-control" {{ $isEditingDevice ? "disabled" : "" }}>
                                <option value="onoff">ON/OFF</option>
                                <option value="remote">Remote</option>
                                <option value="cctv">CCTV</option>
                            </select>
                            @if ($isEditingDevice)
                                <small class="text-muted">Tipe device tidak dapat diubah saat edit</small>
                            @endif
                        </div>

                        <!-- Remote Type (only shown when tipe = remote) -->
                        @if ($tipe_device === "remote")
                            <div class="form-group">
                                <label>Tipe Remote</label>
                                <select wire:model="remote_type" class="form-control">
                                    <option value="">-- Pilih Tipe Remote --</option>
                                    <option value="ac">🌡️ AC (Air Conditioner)</option>
                                    <option value="tv">📺 TV (Televisi)</option>
                                </select>
                                @error("remote_type")
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @endif

                        <!-- Icon Device -->
                        <div class="form-group">
                            <label>Icon</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 45px; justify-content: center">
                                        <i class="{{ $icon_device ?: "fas fa-plug" }}"></i>
                                    </span>
                                </div>
                                <select wire:model.live="icon_device" class="form-control">
                                    <option value="">-- Pilih Icon --</option>
                                    <optgroup label="Lampu & Listrik">
                                        <option value="fas fa-lightbulb">💡 Lampu</option>
                                        <option value="fas fa-plug">🔌 Colokan</option>
                                        <option value="fas fa-bolt">⚡ Listrik</option>
                                        <option value="fas fa-charging-station">🔋 Charging</option>
                                    </optgroup>
                                    <optgroup label="AC & Kipas">
                                        <option value="fas fa-fan">🌀 Kipas</option>
                                        <option value="fas fa-snowflake">❄️ AC</option>
                                        <option value="fas fa-temperature-low">🌡️ Suhu</option>
                                        <option value="fas fa-wind">💨 Angin</option>
                                    </optgroup>
                                    <optgroup label="Hiburan">
                                        <option value="fas fa-tv">📺 TV</option>
                                        <option value="fas fa-volume-up">🔊 Speaker</option>
                                        <option value="fas fa-gamepad">🎮 Remote</option>
                                        <option value="fas fa-music">🎵 Musik</option>
                                    </optgroup>
                                    <optgroup label="Keamanan">
                                        <option value="fas fa-video">📹 Kamera</option>
                                        <option value="fas fa-door-open">🚪 Pintu</option>
                                        <option value="fas fa-lock">🔒 Kunci</option>
                                        <option value="fas fa-bell">🔔 Bel</option>
                                    </optgroup>
                                    <optgroup label="Lainnya">
                                        <option value="fas fa-water">💧 Air</option>
                                        <option value="fas fa-fire">🔥 Api</option>
                                        <option value="fas fa-wifi">📶 WiFi</option>
                                        <option value="fas fa-cog">⚙️ Umum</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <!-- ON/OFF Fields -->
                        @if ($tipe_device === "onoff")
                            <fieldset class="border rounded p-3 mb-3">
                                <legend class="w-auto px-2 mb-0" style="font-size: 14px; font-weight: 600">Konfigurasi Power</legend>
                                <div class="form-group">
                                    <label>MQTT Topic</label>
                                    <input type="text" wire:model="power_topic" class="form-control" placeholder="home/lampu/switch" />
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label>Payload ON</label>
                                            <input type="text" wire:model="power_payload_on" class="form-control" placeholder="ON" />
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label>Payload OFF</label>
                                            <input type="text" wire:model="power_payload_off" class="form-control" placeholder="OFF" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" wire:model="power_retain" class="form-check-input" id="power_retain" />
                                    <label class="form-check-label" for="power_retain">Retain message</label>
                                </div>
                            </fieldset>
                        @endif

                        <!-- Remote Fields -->
                        @if ($tipe_device === "remote")
                            <fieldset class="border rounded p-3 mb-3">
                                <legend class="w-auto px-2 mb-0" style="font-size: 14px; font-weight: 600">Konfigurasi Remote</legend>
                                <div class="form-group mb-0">
                                    <label>MQTT Topic</label>
                                    <input type="text" wire:model="remote_topic" class="form-control" placeholder="home/ac/control" />
                                    <small class="text-muted">Tombol-tombol remote dapat diatur setelah device dibuat</small>
                                </div>
                            </fieldset>
                        @endif

                        <!-- kWh Monitoring -->
                        <fieldset class="border rounded p-3">
                            <legend class="w-auto px-2 mb-0" style="font-size: 14px; font-weight: 600">Monitoring</legend>
                            <div class="form-check">
                                <input type="checkbox" wire:model.live="kwh_enabled" class="form-check-input" id="kwh_enabled" />
                                <label class="form-check-label" for="kwh_enabled">Aktifkan kWh Monitoring</label>
                            </div>
                            @if ($kwh_enabled)
                                <div class="form-group mt-3 mb-0">
                                    <label>MQTT Topic kWh</label>
                                    <input type="text" wire:model="kwh_topic" class="form-control" placeholder="home/lampu/kwh" />
                                </div>
                            @endif
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="cancelDevice()" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" wire:click.prevent="{{ $isEditingDevice ? "updateDevice()" : "storeDevice()" }}" wire:loading.attr="disabled" class="btn btn-primary">
                            <span wire:loading.remove>{{ $isEditingDevice ? "Simpan Perubahan" : "Tambah Device" }}</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push("general-css")
    <link rel="stylesheet" href="{{ asset("assets/midragon/select2/select2.min.css") }}" />
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush

@push("scripts")
    <script src="{{ asset("assets/midragon/select2/select2.full.min.js") }}"></script>
    <script>
        $(document).ready(function() {
            // Variables to store active tab state
            let activeDeviceTab = 'power-tab';
            let activeRuanganTab = 'list-tab';

            // Save tab state when tab is clicked
            $('button[data-toggle="pill"]').on('shown.bs.tab', function(e) {
                activeDeviceTab = e.target.id;
            });

            $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
                activeRuanganTab = e.target.id;
            });

            // Restore tab state after Livewire updates
            Livewire.hook('message.processed', (message, component) => {
                // Restore device tabs
                if (activeDeviceTab && document.getElementById(activeDeviceTab)) {
                    setTimeout(() => {
                        $('#' + activeDeviceTab).tab('show');
                    }, 50);
                }

                // Restore ruangan tabs (only when modal is open)
                if ($('#ruangModal').hasClass('show') && activeRuanganTab && document.getElementById(activeRuanganTab)) {
                    setTimeout(() => {
                        $('#' + activeRuanganTab).tab('show');
                    }, 50);
                }
            });

            // Init Select2 when modal opens
            $('#formDeviceModal').on('shown.bs.modal', function() {
                $('#selectRuang').select2({
                    dropdownParent: $('#formDeviceModal'),
                    placeholder: 'Pilih Ruang',
                    width: '100%'
                });
            });

            // Update Livewire when Select2 changes
            $('#selectRuang').on('change', function() {
                @this.set('selectedRoomId', $(this).val());
            });

            // Destroy Select2 when modal closes
            $('#formDeviceModal').on('hidden.bs.modal', function() {
                $('#selectRuang').select2('destroy');
            });
        });
    </script>

    <!-- Paho MQTT Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.1.0/paho-mqtt.min.js"></script>

    <!-- MQTT Connection Alpine.js Component -->
    <script>
        // Global MQTT client
        window.mqttClient = null;

        function mqttConnection() {
            return {
                showModal: true,
                showPassword: false,
                saveCredentials: true,
                connectionStatus: 'idle', // idle, connecting, connected, error
                errorMessage: '',
                isConnected: false,
                mqtt: {
                    hostname: '',
                    port: 9001, // WebSocket port (usually 9001 or 8083)
                    username: '',
                    password: '',
                },

                init() {
                    // Load saved credentials from localStorage
                    const saved = localStorage.getItem('mqttCredentials');
                    if (saved) {
                        try {
                            const creds = JSON.parse(saved);
                            this.mqtt = { ...this.mqtt, ...creds };
                            this.saveCredentials = true;
                            // Only load credentials, don't auto-connect
                            // User must press "Hubungkan" button
                        } catch (e) {
                            console.error('Failed to parse saved MQTT credentials');
                        }
                    }
                },

                connect() {
                    // Validate fields
                    if (!this.mqtt.hostname) {
                        this.connectionStatus = 'error';
                        this.errorMessage = 'Hostname tidak boleh kosong';
                        return;
                    }

                    this.connectionStatus = 'connecting';

                    try {
                        // Create a unique client ID
                        const clientId = 'smarthome_' + Math.random().toString(16).substr(2, 8);

                        // Create MQTT client (WebSocket connection)
                        mqttClient = new Paho.Client(this.mqtt.hostname, parseInt(this.mqtt.port), clientId);

                        // Set callback handlers
                        mqttClient.onConnectionLost = (responseObject) => {
                            this.isConnected = false;
                            this.connectionStatus = 'error';
                            this.errorMessage = 'Koneksi terputus: ' + responseObject.errorMessage;
                            console.log('MQTT Connection lost:', responseObject.errorMessage);
                        };

                        window.mqttDeviceStates = window.mqttDeviceStates || {};

                        mqttClient.onMessageArrived = (message) => {
                            console.log('MQTT Message received:', message.destinationName, message.payloadString);

                            // Cache the state globally
                            window.mqttDeviceStates[message.destinationName] = message.payloadString;

                            // Dispatch event for other components to listen
                            window.dispatchEvent(
                                new CustomEvent('mqtt-message', {
                                    detail: {
                                        topic: message.destinationName,
                                        payload: message.payloadString,
                                    },
                                }),
                            );
                        };

                        // Connect options
                        const connectOptions = {
                            onSuccess: () => {
                                console.log('MQTT Connected successfully!');
                                this.isConnected = true;
                                this.connectionStatus = 'connected';

                                // Save credentials if checkbox is checked
                                if (this.saveCredentials) {
                                    localStorage.setItem('mqttCredentials', JSON.stringify(this.mqtt));
                                }

                                // Close modal after successful connection
                                setTimeout(() => {
                                    this.showModal = false;
                                }, 1000);

                                // Subscribe to smarthome topics
                                mqttClient.subscribe('smarthome/#');
                                console.log('Subscribed to smarthome/#');
                            },
                            onFailure: (error) => {
                                console.error('MQTT Connection failed:', error);
                                this.isConnected = false;
                                this.connectionStatus = 'error';
                                this.errorMessage = 'Gagal terhubung: ' + (error.errorMessage || 'Periksa hostname dan port');
                            },
                            timeout: 10,
                            useSSL: false,
                        };

                        // Add authentication if provided
                        if (this.mqtt.username) {
                            connectOptions.userName = this.mqtt.username;
                        }
                        if (this.mqtt.password) {
                            connectOptions.password = this.mqtt.password;
                        }

                        // Connect
                        mqttClient.connect(connectOptions);
                    } catch (error) {
                        console.error('MQTT Error:', error);
                        this.connectionStatus = 'error';
                        this.errorMessage = 'Error: ' + error.message;
                    }
                },

                disconnect() {
                    if (mqttClient && this.isConnected) {
                        mqttClient.disconnect();
                        this.isConnected = false;
                        this.connectionStatus = 'idle';
                        console.log('MQTT Disconnected');
                    }
                },

                publish(topic, message) {
                    if (mqttClient && this.isConnected) {
                        const mqttMessage = new Paho.Message(message);
                        mqttMessage.destinationName = topic;
                        mqttClient.send(mqttMessage);
                        console.log('MQTT Published:', topic, message);
                    }
                },

                clearSavedCredentials() {
                    localStorage.removeItem('mqttCredentials');
                    this.disconnect();
                    this.mqtt = {
                        hostname: '',
                        port: 9001,
                        username: '',
                        password: '',
                    };
                    this.saveCredentials = true;
                    this.connectionStatus = 'idle';
                    this.errorMessage = '';
                },

                openModal() {
                    this.showModal = true;
                },
            };
        }
    </script>
@endpush
