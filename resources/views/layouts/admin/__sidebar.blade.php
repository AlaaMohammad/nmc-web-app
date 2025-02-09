<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Manage Resources
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="/admin/dashboard">
                    <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('clients.index')}}">
                    <i class="align-middle" data-feather="briefcase"></i> <span class="align-middle">Clients</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('technicians.index')}}">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Technicians</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('work-orders.index')}}">
                    <i class="align-middle" data-feather="list"></i> <span class="align-middle">Work Orders</span>
                </a>
            </li>
          </ul>

    </div>
</nav>
