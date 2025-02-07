<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
           {{-- @if(\Illuminate\Support\Facades\Auth::user()->is_admin == '1')--}}
                <div class="nav accordion" id="accordionSidenav">
                    <div class="sidenav-menu-heading">Control Panel Pages</div>

                    <a class="nav-link" href="/admin">
                        <div class="nav-link-icon"><i class="fas fa-columns text-gray-200"></i></div>
                        Main Page
                    </a>

                    
                    <a class="nav-link collapsed" href="/!#" data-toggle="collapse" data-target="#collapseDashboardsr555" aria-expanded="false" aria-controls="collapseDashboardsr555">
                        <div class="nav-link-icon"><i class="fas fa-users"></i></div>
                        Users
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDashboardsr555" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="{{route('users.clients')}}">
                                Clients

                            </a>
                            <a class="nav-link" href="{{route('users.sellers')}}">
                                Sellers

                            </a>         
                              <a class="nav-link" href="{{route('users.delegates')}}">
                                Delegates

                            </a>
                            <a class="nav-link" href="{{route('users.create')}}">
                                New User 

                            </a>

                        </nav>
                    </div>


                    <a class="nav-link collapsed" href="/!#" data-toggle="collapse" data-target="#collapseDashboardsr5" aria-expanded="false" aria-controls="collapseDashboardsr5">
                        <div class="nav-link-icon"><i class="fas fa-archive"></i></div>
                        Packages
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDashboardsr5" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="{{route('packages.index')}}">
                                All Packages

                            </a>
                            <a class="nav-link" href="{{route('packages.create')}}">
                                New Package 

                            </a>

                        </nav>
                    </div>


                    <a class="nav-link collapsed" href="/!#" data-toggle="collapse" data-target="#collapseDashboardsr6" aria-expanded="false" aria-controls="collapseDashboardsr6">
                        <div class="nav-link-icon"><i class="fas fa-list-ul"></i></div>
                        Sections
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDashboardsr6" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="{{route('sections.index')}}">
                                All Sections

                            </a>
                            <a class="nav-link" href="{{route('sections.create')}}">
                                New Section 

                            </a>

                        </nav>
                    </div>



                    <a class="nav-link collapsed" href="/!#" data-toggle="collapse" data-target="#collapseDashboardsr7" aria-expanded="false" aria-controls="collapseDashboardsr7">
                        <div class="nav-link-icon"><i class="fas fa-store"></i></div>
                        Stores
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDashboardsr7" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="{{route('stores.index')}}">
                                All Stores

                            </a>

                            <a class="nav-link" href="{{route('stores.showSellersRequests')}}">
                                 Seller/Store requests

                            </a>
                            <a class="nav-link" href="{{route('stores.create')}}">
                                New Store 

                            </a>

                        </nav>
                    </div>













                    <a class="nav-link collapsed" href="/!#" data-toggle="collapse" data-target="#collapseDashboardsr2" aria-expanded="false" aria-controls="collapseDashboardsr2">
                        <div class="nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                        Invoices
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDashboardsr2" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="#">
                                All Invoices

                            </a>
                            <a class="nav-link" href="#">
                                New Invoice 

                            </a>

                        </nav>
                    </div>



                    <a class="nav-link collapsed" href="/!#" data-toggle="collapse" data-target="#collapseDashboardsr3" aria-expanded="false" aria-controls="collapseDashboardsr3">
                        <div class="nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Subscriptions
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDashboardsr3" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="{{route('subscriptions.guestSubscriptions')}}">
                                Guests Subscriptions

                            </a>
                            <a class="nav-link" href="{{route('subscriptions.userSubscriptions')}}">
                                Users Subscriptions 

                            </a>

                        </nav>
                    </div>


                    <a class="nav-link collapsed" href="/!#" data-toggle="collapse" data-target="#collapseDashboardsr4" aria-expanded="false" aria-controls="collapseDashboardsr4">
                        <div class="nav-link-icon"><i class="fas fa-list"></i></div>
                        Panel Options
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDashboardsr4" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="{{route('options.index')}}">
                                All Options

                            </a>

                            <a class="nav-link" href="{{route('options.showImages')}}">
                                Images Options

                            </a>
                            <a class="nav-link" href="{{route('options.create')}}">
                                New Option 

                            </a>

                        </nav>
                    </div>


                    <a class="nav-link collapsed" href="/!#" data-toggle="collapse" data-target="#collapseDashboardsr8" aria-expanded="false" aria-controls="collapseDashboardsr8">
                        <div class="nav-link-icon"><i class="far fa-newspaper"></i></div>
                        Latest News
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDashboardsr8" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="{{route('news.index')}}">
                                All News

                            </a>
                            <a class="nav-link" href="{{route('news.create')}}">
                                New Article 

                            </a>

                        </nav>
                    </div>


                    <a class="nav-link collapsed" href="/!#" data-toggle="collapse" data-target="#collapseDashboardsr10" aria-expanded="false" aria-controls="collapseDashboardsr10">
                        <div class="nav-link-icon"><i class="fas fa-mail-bulk"></i></div>
                        Tickets
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDashboardsr10" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="{{route('tickets.index')}}">
                                All Tickets

                            </a>
                  

                        </nav>
                    </div>



                    <a class="nav-link collapsed" href="/!#" data-toggle="collapse" data-target="#collapseDashboardsr11" aria-expanded="false" aria-controls="collapseDashboardsr11">
                        <div class="nav-link-icon"><i class="fas fa-percent"></i></div>
                        Offers
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDashboardsr11" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="{{route('offers.index')}}">
                                All Offers

                            </a>

                            <a class="nav-link" href="{{route('offers.create')}}">
                                New Offer

                            </a>

                            <a class="nav-link" href="{{route('offerNotifications.showChangeDiscountRequests')}}">
                               Changing offer discount requests

                            </a>
                  

                        </nav>
                    </div>

                    <a class="nav-link collapsed" href="/!#" data-toggle="collapse" data-target="#collapseDashboardsr12" aria-expanded="false" aria-controls="collapseDashboardsr12">
                        <div class="nav-link-icon"><i class="fas fa-sliders-h"></i></div>
                        On Boarding Screen
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDashboardsr12" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="{{route('onboardings.index')}}">
                                All Slides

                            </a>

                        
                  

                        </nav>
                    </div>





                </div>

          {{--  @endif--}}

        </div>
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">admin name</div>
                <div class="sidenav-footer-title">{{Auth::user()->first_name}}</div>
            </div>
        </div>
    </nav>
</div>
