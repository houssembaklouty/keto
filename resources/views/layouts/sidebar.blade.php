<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('home') }}" class="brand-link">
        <img src="https://ui-avatars.com/api/?name=admin"
             alt="{{ config('app.name') }} Logo"
             class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
            <li class="nav-item">
                <a href="{{ route('orders.index') }}"
                class="nav-link {{ Request::is('orders*') ? 'active' : '' }}">
                    <p>Orders</p>
                </a>
            </li>

            </ul>
        </nav>
    </div>

</aside>
