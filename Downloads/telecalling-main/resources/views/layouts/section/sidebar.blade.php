<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        {{-- <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('admin/assets/img/logo.svg') }}" style="width:120px;">
            </span>
        </a> --}}

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        {{-- Dashboard --}}
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        {{-- Companies --}}
        <li class="menu-item {{ request()->routeIs('company.list') ? 'active' : '' }}">
            <a href="{{ route('company.list') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-buildings"></i>
                <div data-i18n="Companies">Companies</div>
            </a>
        </li>

        {{-- Agents --}}
        <li class="menu-item {{ request()->routeIs('agent.list') ? 'active' : '' }}">
            <a href="{{ route('agent.list') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Agents">Agents</div>
            </a>
        </li>


        {{-- Sheets --}}
        <li class="menu-item {{ request()->routeIs('sheat.list') ? 'active' : '' }}">
            <a href="{{ route('sheat.list') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-spreadsheet"></i>
                <div data-i18n="Sheets">Sheets</div>
            </a>
        </li>

        {{-- subscriptions --}}

        <li class="menu-item {{ request()->routeIs('subscription.list') ? 'active' : '' }}">
            <a href="{{ route('subscription.list') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div data-i18n="subscriptions">subscriptions</div>
            </a>
        </li>

        {{-- Attach Plan to Company --}}
        <li class="menu-item {{ request()->routeIs('attach.plan') ? 'active' : '' }}">
            <a href="{{ route('attach.plan') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-link"></i>
                <div data-i18n="Attach Plan">Attach Plan</div>
            </a>
        </li>
    </ul>
</aside>
