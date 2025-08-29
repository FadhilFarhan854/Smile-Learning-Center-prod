<!DOCTYPE html>
<html lang="en">

<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Bimbel</title>
    
    <!-- Custom fonts for this template -->
    <link href="{{asset('admin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="{{asset('admin/css/sb-admin-2.css')}}" rel="stylesheet">
    
    <!-- Custom styles for this page -->
    <link href="{{asset('admin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

    
    
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    
            <!-- Sidebar - Brand -->
            @include('include.brand')
            
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            
            <!-- Nav Item - Dashboard 
            <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                <a class="nav-link" href="{{url('/')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>-->
            
            <!-- Divider -->
            <hr class="sidebar-divider">
            
            <!-- Nav Item - Pages Collapse Menu -->
            @if (auth()->user()->role != 'admin' && auth()->user()->role != 'guru' && auth()->user()->role != 'motivator')
            <li class="nav-item {{ Str::startsWith(request()->path(), 'user') ? 'active' : '' }}">
                <a class="nav-link " href="{{url('user')}}" >
                    <i class="fas fa-fw fa-users"></i>
                    <span>User</span>
                </a>
            </li>
            @endif

            @if (auth()->user()->role != 'admin' && auth()->user()->role != 'guru' && auth()->user()->role != 'motivator')
            <li class="nav-item {{ Str::startsWith(request()->path(), 'unit') ? 'active' : '' }}">
                <a class="nav-link " href="{{url('unit')}}" >
                    <i class="fas fa-fw fa-home"></i>
                    <span>Unit</span>
                </a>
            </li>
            @endif

            @if (auth()->user()->role != 'admin' && auth()->user()->role != 'guru' && auth()->user()->role != 'motivator')
            <li class="nav-item {{ Str::startsWith(request()->path(), 'kelas') ? 'active' : '' }}">
                <a class="nav-link " href="{{url('kelas')}}" >
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Kelas</span>
                </a>
            </li>
            @endif
            
            <!-- Nav Item - Utilities Collapse Menu -->
            @if (auth()->user()->role != 'motivator')
            <li class="nav-item {{ Str::startsWith(request()->path(), 'siswa') ? 'active' : '' }}">
                <a class="nav-link " href="{{url('siswa')}}" >
                    <i class="fas fa-fw fa-child"></i>
                    <span>Siswa </span>
                </a>
            </li>
            @endif
        
            <li class="nav-item {{ Str::startsWith(request()->path(), 'absen') ? 'active' : '' }}">
                <a class="nav-link " href="{{url('absen')}}" >
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Absensi Siswa</span>
                </a>
            </li>
            
            <!-- Divider -->
            <hr class="sidebar-divider">
            
            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>
            
            @if (auth()->user()->role != 'admin' && auth()->user()->role != 'guru' && auth()->user()->role != 'motivator')
            <li class="nav-item {{ Str::startsWith(request()->path(), 'modul') ? 'active' : '' }}">
                <a class="nav-link " href="{{url('modul')}}" >
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Modul</span>
                </a>
            </li>
            @endif

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item {{ Str::startsWith(request()->path(), 'order') ? 'active' : '' }}">
                <a class="nav-link " href="{{url('order')}}" >
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Order</span>
                </a>
            </li>
            @if (auth()->user()->role != 'guru' && auth()->user()->role != 'motivator')
            <li class="nav-item {{ Str::startsWith(request()->path(), 'reaktifasi') ? 'active' : '' }}">
                <a class="nav-link " href="{{url('reaktifasi')}}" >
                    <i class="fas fa-fw fa-user-plus"></i>
                    <span>Reaktifasi Siswa</span>
                </a>
            </li>
            @endif
            
            <!-- Nav Item - Charts -->
            {{--<li class="nav-item {{ Str::startsWith(request()->path(), 'laporan') ? 'active' : '' }}">
                <a class="nav-link" href="{{url('laporan')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Laporan</span>
                </a>
            </li>--}}
            
            
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
            
            
            
        </ul>
        
        
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('include.nav')
                <!-- Main Content -->
                @yield('content')
                <!-- End of Main Content -->
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020 {{auth()->user()->notifAmount()}}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
            
        </div>
        <!-- End of Content Wrapper -->
        
    </div>
    <!-- End of Page Wrapper -->
    
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('admin/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    
    <!-- Core plugin JavaScript-->
    <script src="{{asset('admin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    
    <!-- Custom scripts for all pages-->
    <script src="{{asset('admin/js/sb-admin-2.min.js')}}"></script>
    
    <!-- Page level plugins -->
    <script src="{{asset('admin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    
    <!-- Page level custom scripts -->
    <script src="{{asset('admin/js/demo/datatables-demo.js')}}"></script>
	
	<script>
		var csrfToken = "{{csrf_token()}}";
		$(document).ready(function(){
    // Prevent dropdown from closing when clicking inside it
    $('.dropdown-menu').on('click', function(event){
        event.stopPropagation();
    });
});

	$('.dibaca-span').on('click', function(){
    var id = $(this).data('id');
    var amount = $('#notif-amount').text();
    var notifAmount = parseInt(amount.trim());
    var $span = $(this); // Store the reference to $(this) in a variable

    $.ajax({
        url: 'change-notif/' + id,
        method: 'post',
        headers:{
            'X-CSRF-TOKEN' : csrfToken
        },
        success: function(response){
            console.log(response);
            var newAmount = notifAmount - 1;
            $span.remove(); // Use the stored reference to remove the span
            $('#main-'+id).removeClass('text-primary').addClass('text-secondary');
            $('#notif-amount').text(newAmount);
        },
        error: function(error){
            console.log(error)
        }
    });
    console.log(id);
});



		
		$('.hapus-span').on('click', function(){
		var id = $(this).data('id');
			var span = $(this);
		
		$.ajax({
			url: 'delete-notif/' +id,
			method: 'post',
			headers:{
				'X-CSRF-TOKEN' : csrfToken
			},
			success: function(response){
				console.log(response);
				span.closest('.parent-notif').remove();
			},
			error: function(error){
				console.log(error)
			}
		});
		console.log(id);
	})
	</script>
	
    @yield('script')
</body>

</html>