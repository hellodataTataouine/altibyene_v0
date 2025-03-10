<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo e(config('app.name', 'Custom Installer')); ?> - Installer</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" href="<?php echo e(asset('uploads/website-images/favicon.png')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/fontawesome-all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('global/toastr/toastr.min.css')); ?>">
    <style>
        a{
            text-decoration: none;
        }
        .progressbar {
            counter-reset: step;
        }

        .progressbar li {
            list-style-type: none;
            float: left;
            width: 14.2857142857%;
            position: relative;
            text-align: center;
        }

        .progressbar li:before {
            content: counter(step);
            counter-increment: step;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border: 1px solid var(--bs-gray-500);
            display: block;
            text-align: center;
            margin: 0 auto 10px auto;
            border-radius: 50%;
            background-color: var(--bs-light);
        }

        .progressbar li:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 1px;
            background-color: var(--bs-gray-500);
            top: 15px;
            left: -50%;
            z-index: -1;
        }

        .progressbar li:first-child:after {
            content: none;
        }

        .progressbar li.active {
            color: var(--bs-primary);
        }

        .progressbar li.active:before {
            border-color: var(--bs-primary);
        }

        .progressbar li.active+li:after {
            background-color: var(--bs-primary);
        }

        @media screen and (max-width: 767px) {
            .progressbar li span {
                display: none;
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <main class="container mt-5 main">
        <h1 class="text-center text-uppercase text-primary">Installer</h1>

        <div class="row">
            <ul class="progressbar">
                <ul class="progressbar">
                    <li class="<?php if(request()->routeIs('setup.verify') || (session()->has('step-1-complete') && session()->get('step-1-complete'))): ?> active <?php endif; ?>"><a href="<?php echo e(route('setup.verify')); ?>">Verification</a></li>

                    <li class="<?php if(request()->routeIs('setup.requirements') ||
                            (session()->has('step-2-complete') && session()->get('step-2-complete'))): ?> active <?php endif; ?>"><a href="<?php if((session()->has('step-1-complete') && session()->get('step-1-complete'))): ?> <?php echo e(route('setup.requirements')); ?> <?php else: ?> # <?php endif; ?>" class="<?php if(!session()->has('step-1-complete')): ?> text-muted <?php endif; ?>">Requirements</a></li>

                    <li class="<?php if(request()->routeIs('setup.database') || (session()->has('step-3-complete') && session()->get('step-3-complete'))): ?> active <?php endif; ?>"><a href="<?php if((session()->has('step-2-complete') && session()->get('step-2-complete') && session()->has('requirements-complete') && session()->get('requirements-complete'))): ?> <?php echo e(route('setup.database')); ?> <?php else: ?> # <?php endif; ?>" class="<?php if(!session()->has('requirements-complete')): ?> text-muted <?php endif; ?>">Database Setup</a></li>

                    <li class="<?php if(request()->routeIs('setup.account') || (session()->has('step-4-complete') && session()->get('step-4-complete'))): ?> active <?php endif; ?>"><a href="<?php if((session()->has('step-3-complete') && session()->get('step-3-complete'))): ?> <?php echo e(route('setup.account')); ?> <?php else: ?> # <?php endif; ?>" class="<?php if(!session()->has('step-3-complete')): ?> text-muted <?php endif; ?>">Account Setup</a></li>

                    <li class="<?php if(request()->routeIs('setup.configuration') ||
                            (session()->has('step-5-complete') && session()->get('step-5-complete'))): ?> active <?php endif; ?>"><a href="<?php if((session()->has('step-4-complete') && session()->get('step-4-complete'))): ?> <?php echo e(route('setup.configuration')); ?> <?php else: ?> # <?php endif; ?>" class="<?php if(!session()->has('step-4-complete')): ?> text-muted <?php endif; ?>">Configuration</a></li>

                    <li class="<?php if(request()->routeIs('setup.smtp') ||
                            (session()->has('step-6-complete') && session()->get('step-6-complete'))): ?> active <?php endif; ?>"><a href="<?php if((session()->has('step-5-complete') && session()->get('step-5-complete'))): ?> <?php echo e(route('setup.smtp')); ?> <?php else: ?> # <?php endif; ?>" class="<?php if(!session()->has('step-5-complete')): ?> text-muted <?php endif; ?>">SMTP Setup</a></li>

                    <li class="<?php if(request()->routeIs('setup.complete') || (session()->has('step-7-complete') && session()->get('step-7-complete'))): ?> active <?php endif; ?>"><a href="<?php if((session()->has('step-6-complete') && session()->get('step-6-complete'))): ?> <?php echo e(route('setup.complete')); ?> <?php else: ?> # <?php endif; ?>" class="<?php if(!session()->has('step-6-complete')): ?> text-muted <?php endif; ?>">Complete</a></li>
                </ul>
            </ul>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <?php if($errors->any()): ?>
                    <div class="mb-1 card">
                        <div class="card-body text-danger">
                            <?php echo e($errors->first()); ?>

                        </div>
                    </div>
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>

    </main>
</body>
<script src="<?php echo e(asset('global/js/jquery-3.7.1.min.js')); ?>"></script>
<script src="<?php echo e(asset('global/toastr/toastr.min.js')); ?>"></script>
<script src="<?php echo e(asset('frontend/js/bootstrap.min.js')); ?>"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const makeAjaxRequest = (formData, actionUrl) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: actionUrl,
                method: "post",
                data: formData,
                success: function(res) {
                    resolve(res);
                },
                error: function(err) {
                    reject(err);
                }
            });
        });
    }
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>

</html><?php /**PATH C:\project_2025\laravel\altibyene_v0\Modules/Installer\resources/views/app.blade.php ENDPATH**/ ?>