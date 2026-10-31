<?php $__env->startSection('title', $_title); ?>
<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <?php echo $__env->make('templates.header-action', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </section>

    <!-- Main content -->
    <section class="content appTuyenSinh">
        <link rel="stylesheet" href="<?php echo e(asset('default/bower_components/select2/dist/css/select2.min.css')); ?> ">
        <style>
            .select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
                padding: 3px 0px;
                height: 30px;
            }
            .select2-container {
                margin-top: -5px;
            }

            option {
                white-space: nowrap;
            }

            .select2-container--default .select2-selection--single {
                background-color: #fff;
                border: 1px solid #aaa;
                border-radius: 0px;
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                color: #216992;
            }
            .select2-container--default .select2-selection--multiple{
                margin-top:10px;
                border-radius: 0;
            }
            .select2-container--default .select2-results__group{
                background-color: #eeeeee;
            }
        </style>

        <?php //Hiển thị thông báo thành công?>
        <?php if( Session::has('success') ): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <strong><?php echo e(Session::get('success')); ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
        <?php endif; ?>
        <?php //Hiển thị thông báo lỗi?>
        <?php if( Session::has('error') ): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <strong><?php echo e(Session::get('error')); ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
    <?php endif; ?>

    <!-- Phần nội dung riêng của action  -->
        <form class="form-horizontal " action="<?php echo e(route('route_BackEnd_AdminNhanVienTaiKhoan_Update',['id'=>request()->route('id')])); ?>" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ten_de_thi" class="col-md-3 col-sm-4 control-label">Tên người dùng <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="name" id="name" class="form-control" value="<?php if(isset($request['name'])): ?>  <?php echo e($request['name']); ?> <?php else: ?> <?php echo e($objItem->name); ?> <?php endif; ?>" <?php if($objItem->name != ''): ?>  <?php endif; ?>>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-3 col-sm-4 control-label">Email <span class="text-danger">(*)</span></label>
                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="email" id="email" class="form-control" value="<?php if(isset($request['email'])): ?>  <?php echo e($request['email']); ?> <?php else: ?> <?php echo e($objItem->email); ?> <?php endif; ?>" <?php if($objItem->email != ''): ?>  <?php endif; ?> disabled>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-3 col-sm-4 control-label">Mật khẩu </label>
                            <div class="col-md-9 col-sm-8">
                                <input type="password" name="password" id="password" class="form-control" value="<?php if(isset($request['password'])): ?><?php echo e($request['password']); ?><?php endif; ?>">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-3 col-sm-4 control-label">Nhập lại Mật khẩu </label>
                            <div class="col-md-9 col-sm-8">
                                <input type="password" name="password1" id="password1" class="form-control" value="<?php if(isset($request['password'])): ?><?php echo e($request['password']); ?><?php endif; ?>">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>

                    </div>
                    
                    <div class="col-md-6">

                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary"> Save</button>
                <a href="<?php echo e(route('route_BackEnd_NguoiDung_index')); ?>" class="btn btn-default">Cancel</a>
            </div>
            <!-- /.box-footer -->
        </form>

    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('default/plugins/input-mask/jquery.inputmask.js')); ?>"></script>
    <script src="<?php echo e(asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js')); ?>"></script>
    
    

<?php $__env->stopSection(); ?>


<?php echo $__env->make('templates.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Download\QuanLyNhanSu\QuanLyNhanSu\resources\views/nhanvien/fr-tai-khoan-nhan-vien.blade.php ENDPATH**/ ?>