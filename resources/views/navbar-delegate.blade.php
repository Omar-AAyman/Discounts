<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
           {{-- @if(\Illuminate\Support\Facades\Auth::user()->is_admin == '1')--}}
                <div class="nav accordion" id="accordionSidenav">
                    <div class="sidenav-menu-heading">Control Panel Pages</div>

                    <a class="nav-link" href="{{route('delegates.mainView')}}">
                        <div class="nav-link-icon"><i class="fas fa-columns text-gray-200"></i></div>
                        Main Page
                    </a>




                    <a class="nav-link collapsed" href="/!#" data-toggle="collapse" data-target="#collapseDashboardsr5" aria-expanded="false" aria-controls="collapseDashboardsr5">
                        <div class="nav-link-icon"><i class="fas fa-archive"></i></div>
                        Sellers
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDashboardsr5" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="{{route('delegates.relatedSellers')}}">
                                Related Sellers

                             </a>
                            <a class="nav-link" href="{{route('delegates.createSeller')}}">
                               Add New Seller

                            </a>

                        </nav>
                    </div>




                </div>

          {{--  @endif--}}

        </div>
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle"></div>
                <div class="sidenav-footer-title"></div>
            </div>
        </div>
    </nav>
</div>
