@extends('layout')

@section('content')

    <main>


        <!-- Main page content-->
        <div class="container mt-n5">

            <div class="row">
                <div class="col-xl-4 mb-4">
                    <!-- Dashboard example card 1-->
                    <a class="card lift h-100" href="#!">
                        <div class="card-body d-flex justify-content-center flex-column">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="me-3">
                                    <i class="feather-xl text-primary mb-3" data-feather="package"></i>
                                    <h5>Powerful Components</h5>
                                    <div class="text-muted small">To create informative visual elements on your pages</div>
                                </div>
                                <img src="assets/img/illustrations/browser-stats.svg" alt="..." style="width: 8rem" />
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 mb-4">
                    <!-- Dashboard example card 2-->
                    <a class="card lift h-100" href="#!">
                        <div class="card-body d-flex justify-content-center flex-column">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="me-3">
                                    <i class="feather-xl text-secondary mb-3" data-feather="book"></i>
                                    <h5>Flixable system</h5>
                                    <div class="text-muted small">To keep you on track when working with our toolkit</div>
                                </div>
                                <img src="assets/img/illustrations/processing.svg" alt="..." style="width: 8rem" />
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 mb-4">
                    <!-- Dashboard example card 3-->
                    <a class="card lift h-100" href="#!">
                        <div class="card-body d-flex justify-content-center flex-column">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="me-3">
                                    <i class="feather-xl text-green mb-3" data-feather="layout"></i>
                                    <h5>Easy to Use!</h5>
                                    <div class="text-muted small">To help get you started when building your new UI</div>
                                </div>
                                <img src="assets/img/illustrations/windows.svg" alt="..." style="width: 8rem" />
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="  ">
                <div class="row">
                    <div class="col-xxl-4 col-xl-12 mb-4">
                        <div class="card h-100">
                            <div class="card-body h-100 p-5">
                                <div class="row align-items-center">
                                    <div class="col-xl-8 col-xxl-12">
                                        <div class="text-center text-xl-start text-xxl-center mb-4 mb-xl-0 mb-xxl-4">
                                            <h1 class="text-primary">Welcome to Pixels System!</h1>
                                            <p class="text-gray-700 mb-0">Browse our fully Gallery UI toolkit! Browse our prebuilt app pages, components, and sections, and be sure to look at our full documentation!</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-xxl-12 text-center"><img class="img-fluid" src="assets/img/illustrations/at-work.svg" style="max-width: 26rem" /></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-xl-6 mb-4">
                        <div class="card card-header-actions h-100">
                            <div class="card-header">
                                Recent Activity
                                <div class="dropdown no-caret">
                                    <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-gray-500" data-feather="more-vertical"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="dropdownMenuButton">
                                        <h6 class="dropdown-header">Filter Activity:</h6>
                                        <a class="dropdown-item" href="#!"><span class="badge bg-green-soft text-green my-1">Commerce</span></a>
                                        <a class="dropdown-item" href="#!"><span class="badge bg-blue-soft text-blue my-1">Reporting</span></a>
                                        <a class="dropdown-item" href="#!"><span class="badge bg-yellow-soft text-yellow my-1">Server</span></a>
                                        <a class="dropdown-item" href="#!"><span class="badge bg-purple-soft text-purple my-1">Users</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="timeline timeline-xs">
                                    <!-- Timeline Item 1-->
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">27 min</div>
                                            <div class="timeline-item-marker-indicator bg-green"></div>
                                        </div>
                                        <div class="timeline-item-content">
                                            New Order placed!
                                            <a class="fw-bold text-dark" href="#!">Order #2912</a>
                                            has been successfully placed.
                                        </div>
                                    </div>
                                    <!-- Timeline Item 2-->
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">58 min</div>
                                            <div class="timeline-item-marker-indicator bg-blue"></div>
                                        </div>
                                        <div class="timeline-item-content">
                                            Your
                                            <a class="fw-bold text-dark" href="#!">weekly report</a>
                                            has been generated and is ready to view.
                                        </div>
                                    </div>
                                    <!-- Timeline Item 3-->
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">2 hrs</div>
                                            <div class="timeline-item-marker-indicator bg-purple"></div>
                                        </div>
                                        <div class="timeline-item-content">
                                            New Artist
                                            <a class="fw-bold text-dark" href="#!">Valerie Luna</a>
                                            has registered
                                        </div>
                                    </div>
                                    <!-- Timeline Item 4-->
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">1 day</div>
                                            <div class="timeline-item-marker-indicator bg-yellow"></div>
                                        </div>
                                        <div class="timeline-item-content">New Gallery has been created</div>
                                    </div>
                                    <!-- Timeline Item 5-->
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">1 day</div>
                                            <div class="timeline-item-marker-indicator bg-green"></div>
                                        </div>
                                        <div class="timeline-item-content">
                                            New Product placed!
                                            <a class="fw-bold text-dark" href="#!">Product #2911</a>
                                            has been successfully placed.
                                        </div>
                                    </div>
                                    <!-- Timeline Item 6-->
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">1 day</div>
                                            <div class="timeline-item-marker-indicator bg-purple"></div>
                                        </div>
                                        <div class="timeline-item-content">
                                            New Podcast Started!
                                            <a class="fw-bold text-dark" href="#!">Podcast #2911</a>
                                            has been successfully placed.
                                        </div>
                                    </div>
                                    <!-- Timeline Item 7-->
                                    <div class="timeline-item">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">2 days</div>
                                            <div class="timeline-item-marker-indicator bg-green"></div>
                                        </div>
                                        <div class="timeline-item-content">
                                            New Product placed!
                                            <a class="fw-bold text-dark" href="#!">Product #2910</a>
                                            has been successfully placed.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-xl-6 mb-4">
                        <div class="card card-header-actions h-100">
                            <div class="card-header">
                                Progress Tracker
                                <div class="dropdown no-caret">
                                    <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-gray-500" data-feather="more-vertical"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#!">
                                            <div class="dropdown-item-icon"><i class="text-gray-500" data-feather="list"></i></div>
                                            Manage Tasks
                                        </a>
                                        <a class="dropdown-item" href="#!">
                                            <div class="dropdown-item-icon"><i class="text-gray-500" data-feather="plus-circle"></i></div>
                                            Add New Task
                                        </a>
                                        <a class="dropdown-item" href="#!">
                                            <div class="dropdown-item-icon"><i class="text-gray-500" data-feather="minus-circle"></i></div>
                                            Delete Tasks
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h4 class="small">
                                    Canceled Orders
                                    <span class="float-end fw-bold">20%</span>
                                </h4>
                                <div class="progress mb-4"><div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div></div>
                                <h4 class="small">
                                    Pending Orders
                                    <span class="float-end fw-bold">40%</span>
                                </h4>
                                <div class="progress mb-4"><div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div></div>
                                <h4 class="small">
                                    Payed Invoices
                                    <span class="float-end fw-bold">60%</span>
                                </h4>
                                <div class="progress mb-4"><div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div></div>
                                <h4 class="small">
                                    Online Product
                                    <span class="float-end fw-bold">80%</span>
                                </h4>
                                <div class="progress mb-4"><div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div></div>
                                <h4 class="small">
                                    Site expenses completed
                                    <span class="float-end fw-bold">Complete!</span>
                                </h4>
                                <div class="progress"><div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>
                            </div>
                            <div class="card-footer position-relative">
                                <div class="d-flex align-items-center justify-content-between small text-body">
                                    <a class="stretched-link text-body" href="#!">Visit Task Center</a>
                                    <i class="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Example Colored Cards for Dashboard Demo-->
                <div class="row">
                    <div class="col-lg-6 col-xl-3 mb-4">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3">
                                        <div class="text-white-75 small">Earnings (Monthly)</div>
                                        <div class="text-lg fw-bold">$40,000</div>
                                    </div>
                                    <i class="feather-xl text-white-50" data-feather="calendar"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between small">
                                <a class="text-white stretched-link" href="#!">View Report</a>
                                <div class="text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-3 mb-4">
                        <div class="card bg-warning text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3">
                                        <div class="text-white-75 small">Earnings (all time)</div>
                                        <div class="text-lg fw-bold">$215,000</div>
                                    </div>
                                    <i class="feather-xl text-white-50" data-feather="dollar-sign"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between small">
                                <a class="text-white stretched-link" href="#!">View Report</a>
                                <div class="text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-3 mb-4">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3">
                                        <div class="text-white-75 small">Active Users</div>
                                        <div class="text-lg fw-bold">1456</div>
                                    </div>
                                    <i class="feather-xl text-white-50" data-feather="check-square"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between small">
                                <a class="text-white stretched-link" href="#!">View Results</a>
                                <div class="text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-3 mb-4">
                        <div class="card bg-danger text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3">
                                        <div class="text-white-75 small">Open Tickets</div>
                                        <div class="text-lg fw-bold">17</div>
                                    </div>
                                    <i class="feather-xl text-white-50" data-feather="message-circle"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between small">
                                <a class="text-white stretched-link" href="#!">View Results</a>
                                <div class="text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Example Charts for Dashboard Demo-->
                <div class="row">
                    <div class="col-xl-6 mb-4">
                        <div class="card card-header-actions h-100">
                            <div class="card-header">
                                Earnings Breakdown
                                <div class="dropdown no-caret">
                                    <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="areaChartDropdownExample" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-gray-500" data-feather="more-vertical"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="areaChartDropdownExample">
                                        <a class="dropdown-item" href="#!">Last 12 Months</a>
                                        <a class="dropdown-item" href="#!">Last 30 Days</a>
                                        <a class="dropdown-item" href="#!">Last 7 Days</a>
                                        <a class="dropdown-item" href="#!">This Month</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#!">Custom Range</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-area"><canvas id="myAreaChart" width="100%" height="30"></canvas></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 mb-4">
                        <div class="card card-header-actions h-100">
                            <div class="card-header">
                                Monthly Revenue
                                <div class="dropdown no-caret">
                                    <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="areaChartDropdownExample" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-gray-500" data-feather="more-vertical"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="areaChartDropdownExample">
                                        <a class="dropdown-item" href="#!">Last 12 Months</a>
                                        <a class="dropdown-item" href="#!">Last 30 Days</a>
                                        <a class="dropdown-item" href="#!">Last 7 Days</a>
                                        <a class="dropdown-item" href="#!">This Month</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#!">Custom Range</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-bar"><canvas id="myBarChart" width="100%" height="30"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">


                <div class="col-xl-4 col-md-6 mb-4">
                    <!-- Dashboard info widget 3-->
                    <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="small font-weight-bold text-primary mb-1">Admin Info</div>
                                    <div class="h5">Admin nama</div>
                                    <div class="small"> <strong>Admin email</strong></div>

                                </div>
                                <div class="ml-2"><i class="fas fa-envelope-open fa-2x text-gray-200"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <!-- Dashboard info widget 3-->
                    <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="small font-weight-bold text-primary mb-1">Users</div>
                                    <div class="small">Total: <strong>6984{{--{{count($users)}}--}}</strong> -
                                        Free Users: <strong>5478{{--{{count(\App\User::where('status', '0')->get())}}--}}</strong> - Paid Users
                                        :<strong>1789</strong> - Ban Users: <strong>254</strong></div>

                                </div>
                                <div class="ml-2"><i class="fas fa-users fa-2x text-gray-200"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <!-- Dashboard info widget 3-->
                    <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="small font-weight-bold text-primary mb-1">Products</div>
                                    <div class="small">

                                        Total: <strong> 244</strong>

                                        - Online: <strong> 198</strong>

                                        - Ban: <strong> 46</strong>

                                        - On Stock: <strong> 200</strong>
                                        - Out Stock: <strong> 46</strong>


                                    </div>



                                </div>
                                <div class="ml-2"><i class="fas fa-users fa-2x text-gray-200"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <!-- Dashboard info widget 3-->
                    <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="small font-weight-bold text-primary mb-1">Orders</div>
                                    <div class="small">

                                        Total: <strong> 55 </strong>
                                        - Done : <strong> 40 </strong>
                                        - Pending : <strong> 15 </strong>

                                    </div>

                                </div>
                                <div class="ml-2"><i class="fas fa-money-check-alt fa-2x text-gray-200"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-4">
                    <!-- Dashboard info widget 1-->
                    <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="small font-weight-bold text-primary mb-1">Support Tickets</div>
                                    <div class="small">

                                        Open: <strong> 4 ticket(s)</strong>
                                        - Closed: <strong> 33 ticket(s)</strong>


                                    </div>

                                </div>
                                <div class="ml-2"><i class="fas fa-file-invoice text-gray-200 fa-2x"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-4">
                    <!-- Dashboard info widget 1-->
                    <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="small font-weight-bold text-primary mb-1">Invoices</div>
                                    <div class="small">

                                        Paid: <strong> 32 invoice(s)</strong>
                                        Unpaid: <strong> 11 invoice(s)</strong>

                                    </div>

                                </div>
                                <div class="ml-2"><i class="fas fa-gift text-gray-200 fa-2x"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-header">

                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#products">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#suppliers">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#categories">Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#employees">Tickets</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#panel-users">Invoices</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane fade show active" id="products">
                        <!-- Products table code here -->

                        <h5 class="card-title small text-blue">Last 5 Products</h5>


                    </div>
                    <div class="tab-pane fade" id="suppliers">

                        <h5 class="card-title small text-blue">Last 5 Users</h5>

                    </div>
                    <div class="tab-pane fade" id="categories">
                        Last 5 Orders               </div>
                    <div class="tab-pane fade" id="employees">
                        Last 5 Tickets                 </div>
                    <div class="tab-pane fade" id="panel-users">
                        Last 5 Invoices                </div>
                </div>
            </div>





        </div>



    </main>



@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize the Bootstrap tabs component
        $('#myTabs').tab();
    });


</script>



