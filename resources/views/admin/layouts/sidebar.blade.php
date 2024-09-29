<div class="sidebar open shadow-sm">
    <div class="logo-details">
        <i class='bx bxl-codepen icon'></i>
        <div class="logo_name">LorStore</div>
        <i class='bx bx-menu-alt-right' id="menuSidebar"></i>
    </div>
    <ul class="nav-list">
        {{-- <li>
            <i class='bx bx-search'></i>
            <input type="text" placeholder="Search...">
            <span class="tooltip">Search</span>
        </li> --}}
        <li>
            <a href="{{ route('admin.dashboard') }}" wire:navigate>
                <i class='bx bx-grid-alt'></i>
                <span class="links_name">Dashboard</span>
            </a>
            <span class="tooltip">Dashboard</span>
        </li>
        <li>
            <a href="{{ route('admin.users.index') }}" wire:navigate>
                <i class='bx bx-user'></i>
                <span class="links_name">Users</span>
            </a>
            <span class="tooltip">Users</span>
        </li>
        <li>
            <a href="{{ route('admin.colors') }}" wire:navigate>
                <i class='bx bx-palette'></i>
                <span class="links_name">Colors</span>
            </a>
            <span class="tooltip">Colors</span>
        </li>
        <li>
            <a href="{{ route('admin.sizes') }}" wire:navigate>
                <i class='bx bx-collapse-alt'></i>
                <span class="links_name">Sizes</span>
            </a>
            <span class="tooltip">Sizes</span>
        </li>
        <li>
            <a href="{{ route('admin.categories') }}" wire:navigate>
                <i class='bx bx-category'></i>
                <span class="links_name">Categories</span>
            </a>
            <span class="tooltip">Categories</span>
        </li>
        <li>
            <a href="{{ route('admin.products') }}" wire:navigate>
                <i class='bx bx-male-female'></i>
                <span class="links_name">Products</span>
            </a>
            <span class="tooltip">Products</span>
        </li>
        <li>
            <a href="{{ route('admin.banners') }}" wire:navigate>
                <i class='bx bx-images'></i>
                <span class="links_name">Banners</span>
            </a>
            <span class="tooltip">Banners</span>
        </li>
        <li>
            <a href="#">
                <i class='bx bx-pie-chart-alt-2'></i>
                <span class="links_name">Analytics</span>
            </a>
            <span class="tooltip">Analytics</span>
        </li>
        <li>
            <a href="#">
                <i class='bx bx-folder'></i>
                <span class="links_name">File Manager</span>
            </a>
            <span class="tooltip">Files</span>
        </li>
        <li>
            <a href="#">
                <i class='bx bx-cart-alt'></i>
                <span class="links_name">Order</span>
            </a>
            <span class="tooltip">Order</span>
        </li>
        <li>
            <a href="#">
                <i class='bx bx-heart'></i>
                <span class="links_name">Saved</span>
            </a>
            <span class="tooltip">Saved</span>
        </li>
        <li>
            <a href="#">
                <i class='bx bx-cog'></i>
                <span class="links_name">Setting</span>
            </a>
            <span class="tooltip">Setting</span>
        </li>
        <li class="profile">
            <div class="profile-details">
                <img class="avatar" src="http://placebeard.it/250/250" alt="">
                <div class="name_job">
                    <p class="name">{{ Auth::user()->name }}</p>
                    <p class="job">Software Engineer</p>
                </div>
            </div>
            <i class='bx bx-dots-horizontal-rounded' id="btn-profile"></i>
        </li>
    </ul>
</div>
