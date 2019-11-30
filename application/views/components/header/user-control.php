<ul class="navbar-nav float-right">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="<?=assets('images/users/profile_pictures/user-default.png')?>" alt="user" class="rounded-circle" width="31">
            <span class="ml-2 user-text font-medium">Fadillah Amin</span><span class="fas fa-angle-down ml-2 user-text"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
            <div class="d-flex no-block align-items-center p-3 mb-2 border-bottom">
                <div class=""><img src="<?=assets('images/users/profile_pictures/user-default.png')?>" alt="user" class="rounded" width="80"></div>
                <div class="ml-2">
                    <h4 class="mb-0">Fadillah Amin</h4>
                    <p class=" mb-0 text-muted">fltchno@gmail.com</p>
                    <!-- <a href="javascript:void(0)" class="btn btn-sm btn-danger text-white mt-2 btn-rounded">View Profile</a> -->
                </div>
            </div>
            <!-- <a class="dropdown-item" href="javascript:void(0)"><i class="ti-settings mr-1 ml-1"></i> Account Setting</a> -->
            <a class="dropdown-item" href="<?=site_url('auth/authentication/signOut')?>"><i class="fa fa-power-off mr-1 ml-1"></i> Logout</a>
        </div>
    </li>
</ul>