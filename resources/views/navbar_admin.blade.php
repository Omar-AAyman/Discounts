<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                @if(Auth::user()->is_admin)

                <div class="sidenav-menu-heading">Control Panel Pages</div>

                <a class="nav-link" href="/admin">
                    <div class="nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="false" aria-controls="collapseUsers">
                    <div class="nav-link-icon"><i class="fas fa-users"></i></div>
                    Users
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseUsers" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion">
                        <a class="nav-link" href="{{route('users.clients')}}"><i class="fas fa-user"></i> Clients</a>
                        <a class="nav-link" href="{{route('users.sellers')}}"><i class="fas fa-store"></i> Sellers</a>
                        <a class="nav-link" href="{{route('users.delegates')}}"><i class="fas fa-user-tie"></i> Delegates</a>
                        <a class="nav-link" href="{{route('users.customer_supports')}}"><i class="fas fa-headset"></i> CS Agents</a>
                        <a class="nav-link" href="{{route('users.create')}}"><i class="fas fa-user-plus"></i> New User</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePackages" aria-expanded="false" aria-controls="collapsePackages">
                    <div class="nav-link-icon"><i class="fas fa-box"></i></div>
                    Packages
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePackages" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion">
                        <a class="nav-link" href="{{route('packages.index')}}"><i class="fas fa-list"></i> All Packages</a>
                        {{-- <a class="nav-link" href="{{route('packages.create')}}"><i class="fas fa-plus-circle"></i> New Package</a> --}}
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSections" aria-expanded="false" aria-controls="collapseSections">
                    <div class="nav-link-icon"><i class="fas fa-th-large"></i></div>
                    Sections
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseSections" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion">
                        <a class="nav-link" href="{{route('sections.index')}}"><i class="fas fa-list"></i> All Sections</a>
                        <a class="nav-link" href="{{route('sections.create')}}"><i class="fas fa-plus-circle"></i> New Section</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStores" aria-expanded="false" aria-controls="collapseStores">
                    <div class="nav-link-icon"><i class="fas fa-store"></i></div>
                    Stores
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseStores" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion">
                        <a class="nav-link" href="{{route('stores.index')}}"><i class="fas fa-list-alt"></i> All Stores</a>
                        <a class="nav-link" href="{{route('store-and-seller.index')}}"><i class="fas fa-store-alt"></i> Stores & Sellers</a>
                        <a class="nav-link" href="{{route('stores.showSellersRequests')}}"><i class="fas fa-clock"></i> Pending Stores</a>
                        <a class="nav-link" href="{{route('stores.deleteRequests')}}"><i class="fas fa-trash"></i> Delete Requests</a>
                        <a class="nav-link" href="{{route('stores.create')}}"><i class="fas fa-plus-circle"></i> New Store</a>
                        <a class="nav-link" href="{{route('stores.showChangeDiscountRequests')}}"><i class="fas fa-exchange-alt"></i> Discount Requests</a>
                    </nav>
                </div>
{{--
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInvoices" aria-expanded="false" aria-controls="collapseInvoices">
                    <div class="nav-link-icon"><i class="fas fa-file-invoice"></i></div>
                    Invoices
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseInvoices" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion">
                        <a class="nav-link" href="#"><i class="fas fa-list"></i> All Invoices</a>
                        <a class="nav-link" href="#"><i class="fas fa-plus-circle"></i> New Invoice</a>
                    </nav>
                </div>
--}}

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLocations" aria-expanded="false" aria-controls="collapseLocations">
                    <div class="nav-link-icon"><i class="fas fa-map-marked-alt"></i></div>
                    Locations
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLocations" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion">
                        <a class="nav-link" href="{{route('cities.index')}}"><i class="fas fa-globe"></i> Cities</a>
                        <a class="nav-link" href="{{route('areas.index')}}"><i class="fas fa-city"></i> Areas</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSubscriptions" aria-expanded="false" aria-controls="collapseSubscriptions">
                    <div class="nav-link-icon"><i class="fas fa-credit-card"></i></div>
                    Subscriptions
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseSubscriptions" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion">
                        <a class="nav-link" href="{{route('subscriptions.guestSubscriptions')}}"><i class="fas fa-user-clock"></i> Guests</a>
                        <a class="nav-link" href="{{route('subscriptions.userSubscriptions')}}"><i class="fas fa-user-check"></i> Users</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOffers" aria-expanded="false" aria-controls="collapseOffers">
                    <div class="nav-link-icon"><i class="fas fa-tags"></i></div>
                    Offers
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseOffers" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion">
                        <a class="nav-link" href="{{route('offers.index')}}"><i class="fas fa-list"></i> All Offers</a>
                        <a class="nav-link" href="{{route('offers.create')}}"><i class="fas fa-plus-circle"></i> New Offer</a>
                        <a class="nav-link" href="{{route('offerNotifications.showChangeDiscountRequests')}}"><i class="fas fa-exchange-alt"></i> Discount Requests</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTickets" aria-expanded="false" aria-controls="collapseTickets">
                    <div class="nav-link-icon"><i class="fas fa-envelope-open-text"></i></div>
                    Tickets
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseTickets" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion">
                        <a class="nav-link" href="{{route('tickets.index')}}"><i class="fas fa-inbox"></i> All Tickets</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePanelOptions" aria-expanded="false" aria-controls="collapsePanelOptions">
                    <div class="nav-link-icon"><i class="fas fa-cogs"></i></div>
                    Panel Options
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePanelOptions" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion">
                        <a class="nav-link" href="{{route('options.index')}}"><i class="fas fa-list"></i> All Options</a>
                        <a class="nav-link" href="{{route('options.showImages')}}"><i class="fas fa-image"></i> Images Options</a>
                        <a class="nav-link" href="{{route('options.create')}}"><i class="fas fa-plus-circle"></i> New Option</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOnboarding" aria-expanded="false" aria-controls="collapseOnboarding">
                    <div class="nav-link-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    Onboarding
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseOnboarding" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion">
                        <a class="nav-link" href="{{route('onboardings.index')}}"><i class="fas fa-list"></i> All Slides</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseNews" aria-expanded="false" aria-controls="collapseNews">
                    <div class="nav-link-icon"><i class="far fa-newspaper"></i></div>
                    News
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseNews" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion">
                        <a class="nav-link" href="{{route('news.index')}}"><i class="fas fa-list-alt"></i> All News</a>
                        <a class="nav-link" href="{{route('news.create')}}"><i class="fas fa-plus-circle"></i> New Article</a>
                    </nav>
                </div>
                @elseif( Auth::user()->type === 'customer_support' )
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTickets" aria-expanded="false" aria-controls="collapseTickets">
                    <div class="nav-link-icon"><i class="fas fa-envelope-open-text"></i></div>
                    Tickets
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseTickets" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion">
                        <a class="nav-link" href="{{route('tickets.index')}}"><i class="fas fa-inbox"></i> All Tickets</a>
                    </nav>
                </div>

                @endif

            </div>
        </div>
        <div class="sidenav-footer py-3 px-3 bg-light border-top">
            <div class="sidenav-footer-content d-flex align-items-center">
                <div class="avatar me-3">
                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                </div>
                <div>
                    <div class="sidenav-footer-title fw-bold text-dark">
                        {{ Auth::user()->fullname }}
                    </div>
                    <div class="sidenav-footer-subtitle text-muted small">
                        {{ Auth::user()->type === 'client' ? 'Admin' : ucwords(str_replace('_', ' ', Auth::user()->type ?? 'User')) }}
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>

<script>
    // First, store the current state in sessionStorage (faster than localStorage for frequent access)
    let activeCollapseState = sessionStorage.getItem('activeCollapse') || localStorage.getItem('activeCollapse');

    // Run this before DOMContentLoaded to prevent FOUC (Flash of Unstyled Content)
    (function() {
        if (activeCollapseState) {
            // Add a style block to immediately show the correct collapse
            const style = document.createElement('style');
            style.textContent = `${activeCollapseState} { display: block !important; }`;
            document.head.appendChild(style);
        }
    })();

    document.addEventListener("DOMContentLoaded", function() {
        const collapses = document.querySelectorAll('.collapse');
        const accordion = document.getElementById('accordionSidenav');
        const currentPath = window.location.pathname;

        // Handle clicks on menu items
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                // Only handle clicks on collapse triggers
                if (this.getAttribute('data-toggle') === 'collapse') {
                    const targetId = this.getAttribute('data-target');
                    // Store state immediately on click
                    sessionStorage.setItem('activeCollapse', targetId);
                    localStorage.setItem('activeCollapse', targetId);
                } else {
                    // If clicking a link inside a collapse, store its parent collapse
                    const parentCollapse = this.closest('.collapse');
                    if (parentCollapse) {
                        sessionStorage.setItem('activeCollapse', `#${parentCollapse.id}`);
                        localStorage.setItem('activeCollapse', `#${parentCollapse.id}`);
                    }
                }
            });
        });

        // Initialize collapse elements
        collapses.forEach(collapse => {
            // Initialize Bootstrap collapse
            const bsCollapse = new bootstrap.Collapse(collapse, {
                toggle: false
            });

            if (activeCollapseState === `#${collapse.id}`) {
                // Show the active collapse
                bsCollapse.show();

                // Update the trigger button state
                const trigger = document.querySelector(`[data-target="#${collapse.id}"]`);
                if (trigger) {
                    trigger.classList.remove('collapsed');
                    trigger.setAttribute('aria-expanded', 'true');
                }
            }

            // Handle collapse events
            collapse.addEventListener('show.bs.collapse', function(event) {
                event.stopPropagation();

                // Close other collapses
                collapses.forEach(other => {
                    if (other !== event.target && bootstrap.Collapse.getInstance(other)) {
                        bootstrap.Collapse.getInstance(other).hide();
                    }
                });

                // Store state
                const targetId = `#${event.target.id}`;
                sessionStorage.setItem('activeCollapse', targetId);
                localStorage.setItem('activeCollapse', targetId);

                // Update trigger
                const trigger = document.querySelector(`[data-target="${targetId}"]`);
                if (trigger) {
                    trigger.classList.remove('collapsed');
                    trigger.setAttribute('aria-expanded', 'true');
                }
            });

            collapse.addEventListener('hide.bs.collapse', function(event) {
                event.stopPropagation();

                // Update trigger
                const trigger = document.querySelector(`[data-target="#${event.target.id}"]`);
                if (trigger) {
                    trigger.classList.add('collapsed');
                    trigger.setAttribute('aria-expanded', 'false');
                }

                // Clear storage if this was the active collapse
                if (sessionStorage.getItem('activeCollapse') === `#${event.target.id}`) {
                    sessionStorage.removeItem('activeCollapse');
                    localStorage.removeItem('activeCollapse');
                }
            });
        });

        // Highlight active menu item
        const currentMenuItem = Array.from(document.querySelectorAll('.nav-link')).find(link => {
            const href = link.getAttribute('href');
            return href && href !== '#' && currentPath.includes(href);
        });

        if (currentMenuItem) {
            currentMenuItem.classList.add('active');

            // If the active item is in a collapse, make sure it's visible
            const parentCollapse = currentMenuItem.closest('.collapse');
            if (parentCollapse) {
                const bsCollapse = bootstrap.Collapse.getInstance(parentCollapse);
                if (bsCollapse) {
                    bsCollapse.show();
                }

                // Store this collapse state
                sessionStorage.setItem('activeCollapse', `#${parentCollapse.id}`);
                localStorage.setItem('activeCollapse', `#${parentCollapse.id}`);

                // Update trigger
                const trigger = document.querySelector(`[data-target="#${parentCollapse.id}"]`);
                if (trigger) {
                    trigger.classList.remove('collapsed');
                    trigger.setAttribute('aria-expanded', 'true');
                }
            }
        }
    });

</script>
