<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="">
    <title><?=env('APP_NAME')?></title>
    <?=css(assets('design/ample/dist/css/style.min.css'))?>
    <?=css(assets('dist/css/ins.css'))?>

    <style>
        * {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="<?php // preloader ?>">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background:url(<?=assets('design/ample/assets/images/background/login-bg.png')?>) no-repeat center center;">
            <div class="auth-box radius-5 ins-smooth-border" style="padding:30px 50px;">
                <div id="loginform">
                    <div class="logo" style="margin-bottom: 30px;">
                        <span class="db"><img src="<?=assets('images/logo/web/logo-svg.svg')?>" style="width:70%;" alt="logo" /></span>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <?php
                                if (flashData('error'))
                                    echo '<div class="alert alert-danger">'.flashData('error').'</div>';
                            ?>
                            <form class="form-horizontal mt-3" id="loginform" action="<?=site_url('auth/authentication/signIn')?>" method="post">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="email" name="umail" class="form-control form-control-lg" placeholder="Email" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input type="password" name="pwd" class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
                                </div>
                                <div class="form-group text-center">
                                    <div class="col-xs-12 pb-3">
                                        <button class="btn btn-block btn-lg btn-info" type="submit">SIGN IN</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?=js(assets('design/ample/assets/libs/jquery/dist/jquery.min.js'));?>
    <?=js(assets('design/ample/assets/libs/popper.js/dist/umd/popper.min.js'));?>
    <?=js(assets('design/ample/assets/libs/bootstrap/dist/js/bootstrap.min.js'));?>

    <script>
        $('[data-toggle="tooltip"]').tooltip();
        $(".preloader").fadeOut();

        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });
    </script>
</body>

</html>
