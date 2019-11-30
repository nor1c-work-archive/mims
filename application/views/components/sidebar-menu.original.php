<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar ps-container ps-theme-default ps-active-y" data-ps-id="cb0e96dc-d3db-eb44-8e94-2eebf72e9abf">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="in">
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=site_url()?>" aria-expanded="false">
                        <i class="mdi mdi-home"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <div class="devider"></div>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-hospital-building"></i>
                        <span class="hide-menu">Location</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="<?=site_url('building')?>" class="sidebar-link">
                                <i class=""></i>
                                <span class="hide-menu">Building</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=site_url('room')?>" class="sidebar-link">
                                <i class="mdi mdi-cart"></i>
                                <span class="hide-menu">Room</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-dictionary"></i>
                        <span class="hide-menu">Master Data</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class=""></i>
                                <span class="hide-menu">Asset Dictionaries</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class=""></i>
                                <span class="hide-menu">Merk/Brand</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="eco-products-cart.html" class="sidebar-link">
                                <i class="mdi mdi-cart"></i>
                                <span class="hide-menu">Supplier/Institution</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <div class="devider"></div>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span class="hide-menu">Inventory</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="<?=site_url('assets/v/instruments')?>" class="sidebar-link">
                                <i class="mdi mdi-content-cut"></i>
                                <span class="hide-menu">Instruments</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=site_url('assets/v/instrument-sets')?>" class="sidebar-link">
                                <i class="fas fa-box"></i>
                                <span class="hide-menu">Instrument Set</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=site_url('assets/v/instrument-boxes')?>" class="sidebar-link">
                                <i class="fas fa-open-box"></i>
                                <span class="hide-menu">Container/Box</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark active" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-tasks"></i>
                        <span class="hide-menu">Activity</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level in">
                        <li class="sidebar-item">
                            <a class="has-arrow sidebar-link active" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-content-cut"></i>
                                <span class="hide-menu">Instrument Piece</span>
                            </a>
                            <ul aria-expanded="false" class="collapse second-level in">
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="sidebar-link">
                                        <i class="mdi mdi-octagram"></i>
                                        <span class="hide-menu"> Mutation</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li> -->

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=site_url('doctors')?>" aria-expanded="false">
                        <i class="fas fa-user-md"></i>
                        <span class="hide-menu">Doctors</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=site_url('tracking/instruments')?>" aria-expanded="false">
                        <i class="fas fa-search"></i>
                        <span class="hide-menu">Asset Tracker</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-chart-bar"></i>
                        <span class="hide-menu">Summary</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class=""></i>
                                <span class="hide-menu">Graph Report</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <div class="devider"></div>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-account-settings-variant"></i>
                        <span class="hide-menu">Access Control</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="<?=site_url('roles')?>" class="sidebar-link">
                                <i class=""></i>
                                <span class="hide-menu">Role</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=site_url('users')?>" class="sidebar-link">
                                <i class="mdi mdi-cart"></i>
                                <span class="hide-menu">User</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-settings"></i>
                        <span class="hide-menu">Configuration</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="eco-products-cart.html" class="sidebar-link">
                                <i class="mdi mdi-cart"></i>
                                <span class="hide-menu">App Setting</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="eco-products-cart.html" class="sidebar-link">
                                <i class="mdi mdi-cart"></i>
                                <span class="hide-menu">Activity Logs</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class=""></i>
                                <span class="hide-menu">Manual</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="eco-products-cart.html" class="sidebar-link">
                                <i class="mdi mdi-cart"></i>
                                <span class="hide-menu">Backup & Restore</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="eco-products-cart.html" class="sidebar-link">
                                <i class="mdi mdi-cart"></i>
                                <span class="hide-menu">Report Bug</span>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>
