<div class="page-content container-fluid">
	<h4>Welcome back, <b>Fadillah Amin</b></h4>
	<hr>
	<!-- <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-dark">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div class="text-white">
                            <h2>12</h2>
                            <h6>Building</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="text-white display-6"><i class="fas fa-building"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-inverse">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div class="text-white">
                            <h2>120</h2>
                            <h6>Room</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="text-white display-6"><i class="fas fa-clone"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

	<div class="quickAccess">
		<!-- left side -->
		<ul>
			<li>
				<a href="<?= site_url('master/v/MIP') ?>">
					<div class="image-cover">
						<img src="<?=base_url('assets/images/logo/web/pieceCatalogue.svg')?>" alt="">
					</div>
					<span>
						Katalog<br>Instrument
					</span>
				</a>
			</li>
			<li>
				<a href="<?= site_url('master/v/MIS') ?>">
					<div class="image-cover">
						<img src="<?=base_url('assets/images/logo/web/setCatalogue.svg')?>" alt="">
					</div>
					<span>
						Katalog<br>Instrument Set
					</span>
				</a>
			</li>
			<li>
				<a href="<?= site_url('assets/v/MIC') ?>">
					<div class="image-cover">
						<img src="<?=base_url('assets/images/logo/web/containerAsset.svg')?>" alt="">
					</div>
					<span>
						Inventaris<br>Container/Box
					</span>
				</a>
			</li>
			<li>
				<a href="<?= site_url('assets/v/MIS') ?>">
					<div class="image-cover">
						<img src="<?=base_url('assets/images/logo/web/setAsset.svg')?>" alt="">
					</div>
					<span>
						Inventaris<br>Instrument Set
					</span>
				</a>
			</li>
			<li>
				<a href="<?= site_url('assets/v/MIP') ?>">
					<div class="image-cover">
						<img src="<?=base_url('assets/images/logo/web/pieceAsset.svg')?>" alt="">
					</div>
					<span>
						Inventaris<br>Instrument
					</span>
				</a>
			</li>
			<li>
				<a href="<?= site_url('tracking') ?>">
					<div class="image-cover">
						<img src="<?=base_url('assets/images/logo/web/assetScan.svg')?>" alt="">
					</div>
					<span>
						Instrument<br>Tracking
					</span>
				</a>
			</li>
		</ul>
	</div>
	<div style="width:20%;float:right;margin-right:15px;">
		<!-- right side -->
		<div class="row">
			<div style="width:100%;">
				<div class="card bg-success">
					<div class="card-body">
						<div class="d-flex no-block align-items-center">
							<div class="text-white">
								<h2><?=$countMIC?></h2>
								<h4>Container/Box</h4>
							</div>
							<div class="ml-auto">
								<span class="text-white display-6"><i class="fas fa-box-open"></i></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div style="width:100%;">
				<div class="card bg-info">
					<div class="card-body">
						<div class="d-flex no-block align-items-center">
							<div class="text-white">
								<h2><?=$countMIS?></h2>
								<h4>Instrument Set</h4>
							</div>
							<div class="ml-auto">
								<span class="text-white display-6"><i class="fas fa-box"></i></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div style="width:100%;">
				<div class="card bg-cyan">
					<div class="card-body">
						<div class="d-flex no-block align-items-center">
							<div class="text-white">
								<h2><?=$countMIP?></h2>
								<h4>Instrument Pieces</h4>
							</div>
							<div class="ml-auto">
								<span class="text-white display-6"><i class="fas fa-cut"></i></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
