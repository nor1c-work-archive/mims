<aside class="left-sidebar" data-sidebarbg="skin6">
	<!-- Sidebar scroll-->
	<div class="scroll-sidebar ps-container ps-theme-default ps-active-y" data-ps-id="cb0e96dc-d3db-eb44-8e94-2eebf72e9abf">
		<!-- Sidebar navigation-->
		<nav class="sidebar-nav">
			<ul id="sidebarnav" class="in">
				<li class="sidebar-item">
					<a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?= site_url() ?>" aria-expanded="false">
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
							<a href="<?=site_url('location/v/building')?>" class="sidebar-link">
								<i class="mdi mdi-octagram"></i>
								<span class="hide-menu"> Building</span>
							</a>
						</li>
						<li class="sidebar-item">
							<a href="<?=site_url('location/v/room')?>" class="sidebar-link">
								<i class="mdi mdi-octagram"></i>
								<span class="hide-menu"> Room</span>
							</a>
						</li>
					</ul>
				</li>

				<div class="devider"></div>

				<!-- <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-hospital-building"></i>
                        <span class="hide-menu">Location</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="<?= site_url('building') ?>" class="sidebar-link">
                                <i class=""></i>
                                <span class="hide-menu">Building</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?= site_url('room') ?>" class="sidebar-link">
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
                                <span class="hide-menu">Merk/Brand</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="eco-products-cart.html" class="sidebar-link">
                                <i class="mdi mdi-cart"></i>
                                <span class="hide-menu">Supplier/Institution</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="eco-products-cart.html" class="sidebar-link">
                                <i class="mdi mdi-octagram"></i>
                                <span class="hide-menu">Doctors</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <div class="devider"></div> -->

				<li class="sidebar-item">
					<a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
						<i class="mdi mdi-book-open"></i>
						<span class="hide-menu">Catalogue</span>
					</a>
					<ul aria-expanded="false" class="collapse first-level">
						<li class="sidebar-item">
							<a href="<?= site_url('master/v/MIP') ?>" class="sidebar-link">
								<i class=""></i>
								<span class="hide-menu">Instrument</span>
							</a>
						</li>
						<li class="sidebar-item">
							<a href="<?= site_url('master/v/MIS') ?>" class="sidebar-link">
								<i class=""></i>
								<span class="hide-menu">Instrument Set</span>
							</a>
						</li>
					</ul>
				</li>

				<li class="sidebar-item">
					<a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
						<i class="mdi mdi-view-dashboard"></i>
						<span class="hide-menu">Inventory</span>
					</a>
					<ul aria-expanded="false" class="collapse first-level">
						<li class="sidebar-item">
							<a href="<?= site_url('assets/v/MIC') ?>" class="sidebar-link">
								<i class="fas fa-open-box"></i>
								<span class="hide-menu">Container/Box</span>
							</a>
						</li>
						<li class="sidebar-item">
							<a href="<?= site_url('assets/v/MIS') ?>" class="sidebar-link">
								<i class="fas fa-box"></i>
								<span class="hide-menu">Set/Kit</span>
							</a>
						</li>
						<li class="sidebar-item">
							<a href="<?= site_url('assets/v/MIP') ?>" class="sidebar-link">
								<i class="mdi mdi-content-cut"></i>
								<span class="hide-menu">Instruments</span>
							</a>
						</li>
					</ul>
				</li>

				<!-- <div class="devider"></div>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-tasks"></i>
                        <span class="hide-menu">Activity</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-playlist-plus"></i>
                                <span class="hide-menu">Operating Mutation</span>
                            </a>
                            <ul aria-expanded="false" class="collapse second-level">
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="sidebar-link">
                                        <i class="mdi mdi-octagram"></i>
                                        <span class="hide-menu"> Request</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="sidebar-link">
                                        <i class="mdi mdi-octagram"></i>
                                        <span class="hide-menu"> Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-playlist-plus"></i>
                                <span class="hide-menu">Assets Mutation</span>
                            </a>
                            <ul aria-expanded="false" class="collapse second-level">
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="sidebar-link">
                                        <i class="mdi mdi-octagram"></i>
                                        <span class="hide-menu"> Request</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="sidebar-link">
                                        <i class="mdi mdi-octagram"></i>
                                        <span class="hide-menu"> Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li> -->

				<div class="devider"></div>

				<li class="sidebar-item">
					<a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
						<i class="mdi mdi-archive"></i>
						<span class="hide-menu">CSSD</span>
					</a>

					<ul aria-expanded="false" class="collapse first-level">
						<li class="sidebar-item">
							<a href="<?= site_url('tracking/v/MIP') ?>" class="sidebar-link">
								<i class="fas fa-search"></i>
								<span class="hide-menu"> Instrument Tracking</span>
							</a>
						</li>
					</ul>
				</li>

				<!-- <div class="devider"></div>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-chart-bar"></i>
                        <span class="hide-menu">Summary</span>
                    </a>
                    
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="<?= site_url('summary/procurements') ?>" class="sidebar-link">
                                <i class="fas fa-search"></i>
                                <span class="hide-menu"> Assets Procurement</span>
                            </a>
                        </li>
                    </ul>
				</li> -->

				<!-- <div class="devider"></div>

				<li class="sidebar-item">
					<a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
						<i class="mdi mdi-settings-box"></i>
						<span class="hide-menu">General Settings</span>
					</a>
					<ul aria-expanded="false" class="collapse first-level">
						<li class="sidebar-item">
							<a href="javascript:void(0)" class="sidebar-link">
								<i class="mdi mdi-octagram"></i>
								<span class="hide-menu"> Merk</span>
							</a>
						</li>
					</ul>
				</li> -->

				<div class="devider"></div>

				<li class="sidebar-item">
					<a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
						<i class="mdi mdi-settings"></i>
						<span class="hide-menu">Configuration</span>
					</a>
					<ul aria-expanded="false" class="collapse first-level">
						<li class="sidebar-item">
							<a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
								<i class="mdi mdi-playlist-plus"></i>
								<span class="hide-menu">Access Control</span>
							</a>
							<ul aria-expanded="false" class="collapse second-level">
								<li class="sidebar-item">
									<a href="javascript:void(0)" class="sidebar-link">
										<i class="mdi mdi-octagram"></i>
										<span class="hide-menu"> Role Setup</span>
									</a>
								</li>
								<li class="sidebar-item">
									<a href="<?=site_url('users')?>" class="sidebar-link">
										<i class="mdi mdi-octagram"></i>
										<span class="hide-menu"> User Setup</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="sidebar-item">
							<a href="javascript:void(0)" class="sidebar-link">
								<i class="mdi mdi-octagram"></i>
								<span class="hide-menu"> Activity Record</span>
							</a>
						</li>
					</ul>
				</li>

			</ul>
		</nav>
	</div>
</aside>
