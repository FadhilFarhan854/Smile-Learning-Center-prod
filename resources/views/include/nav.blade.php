<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    
    
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
								@if(auth()->user()->notifAmount() != 0)
                                <span class="badge badge-danger badge-counter" id="notif-amount">
									
									{{auth()->user()->notifAmount()}}
								
								</span>
								@endif
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Notifikasi
                                </h6>
									@foreach(auth()->user()->notifAll() as $nt)
                                <div class="dropdown-item d-flex align-items-center parent-notif">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">{{$nt->created_at}}</div>
										<span class="{{$nt->status != 1 ? 'text-secondary' : 'text-primary'}} main-span" id="main-{{$nt->id}}"> <b>{{ucwords($nt->type)}}</b> SPP {{$nt->spp->siswa->nama ?? null}} {{$nt->spp->bulan}}/{{$nt->spp->tahun}} Menunggu Verifikasi 
											@if($nt->status == 1)
											<span class="text-warning ml-2 dibaca-span" data-id="{{$nt->id}}" style="cursor:pointer" id="dibaca-span">Sudah Dibaca</span>
											
											@endif
											<span class="text-danger ml-2 hapus-span" data-id="{{$nt->id}}" style="cursor:pointer" id="hapus-span">Hapus</span>
										</span>
                                    </div>
                                </div>
								@endforeach
                    
                                {{--<a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>--}}
                            </div>
                        </li>
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
			 
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{auth()->user()->name}}</span>
                <img class="img-profile rounded-circle"
                src="{{asset('admin/img/undraw_profile.svg')}}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
        
    </ul>
    
</nav>
<!-- End of Topbar -->