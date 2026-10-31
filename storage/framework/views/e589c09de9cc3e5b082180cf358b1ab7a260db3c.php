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
        <form class="form-horizontal " action="<?php echo e(route('route_BackEnd_AdminNhanVien_Update',['id'=>request()->route('id')])); ?>" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ho_ten" class="col-md-3 col-sm-4 control-label">Tên Nhân Viên <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ho_ten" id="ho_ten" class="form-control" value="<?php if(isset($request['ho_ten'])): ?>  <?php echo e($request['ho_ten']); ?> <?php else: ?> <?php echo e($objItem->ho_ten); ?> <?php endif; ?>" <?php if($objItem->ho_ten != ''): ?>  <?php endif; ?> disabled>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ngay_sinh" class="col-md-3 col-sm-4 control-label">Ngày Sinh<span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ngay_sinh" id="ngay_sinh" class="form-control" value="<?php if(isset($request['ngay_sinh'])): ?>  <?php echo e($request['ngay_sinh']); ?> <?php else: ?> <?php echo e($objItem->ngay_sinh); ?> <?php endif; ?>" <?php if($objItem->ngay_sinh != ''): ?>  <?php endif; ?> disabled>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gioi_tinh" class="col-md-3 col-sm-4 control-label">Giới Tính <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="gioi_tinh" id="gioi_tinh" class="form-control" value="<?php if(isset($request['gioi_tinh'])): ?>  <?php echo e($request['gioi_tinh']); ?> <?php else: ?> <?php echo e($objItem->gioi_tinh); ?> <?php endif; ?>" <?php if($objItem->gioi_tinh != ''): ?>  <?php endif; ?> disabled>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dia_chi" class="col-md-3 col-sm-4 control-label">Địa Chỉ <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="dia_chi" id="dia_chi" class="form-control" value="<?php if(isset($request['dia_chi'])): ?>  <?php echo e($request['dia_chi']); ?> <?php else: ?> <?php echo e($objItem->dia_chi); ?> <?php endif; ?>" <?php if($objItem->dia_chi != ''): ?>  <?php endif; ?> disabled>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="so_dien_thoai " class="col-md-3 col-sm-4 control-label">Số Địa Thoại <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control" value="<?php if(isset($request['so_dien_thoai'])): ?>  <?php echo e($request['so_dien_thoai']); ?> <?php else: ?> <?php echo e($objItem->so_dien_thoai); ?> <?php endif; ?>" <?php if($objItem->so_dien_thoai != ''): ?>  <?php endif; ?> disabled>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="so_chung_minh" class="col-md-3 col-sm-4 control-label">Số Chứng Minh Thư <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="so_chung_minh" id="so_chung_minh" class="form-control" value="<?php if(isset($request['so_chung_minh'])): ?>  <?php echo e($request['so_chung_minh']); ?> <?php else: ?> <?php echo e($objItem->so_chung_minh); ?> <?php endif; ?>" <?php if($objItem->so_chung_minh  != ''): ?>  <?php endif; ?> disabled>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-sm-4 control-label">Ảnh CMND/CCCD</label>
                            <div class="col-md-9 col-sm-8">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <img id="mat_truoc_preview" src="<?php echo e($objItem->anh_cmt_truoc? Storage::url($objItem->anh_cmt_truoc):'http://placehold.it/100x100'); ?>" alt="your image"
                                             style="max-width: 200px; height:100px; margin-bottom: 10px;" class="img-fluid"/>
                                        <input type="file" name="anh_cmt_truoc" accept="image/*"
                                               class="form-control-file <?php $__errorArgs = ['cmt_mat_truoc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="cmt_truoc" disabled>
                                        <label for="cmt_truoc">Mặt trước</label><br/>
                                    </div>
                                    <div class="col-xs-6">
                                        <img id="mat_sau_preview" src="<?php echo e($objItem->anh_cmt_sau? Storage::url($objItem->anh_cmt_sau):'http://placehold.it/100x100'); ?>" alt="your image"
                                             style="max-width: 200px; height:100px; margin-bottom: 10px;" class="img-fluid"/>
                                        <input type="file" name="anh_cmt_sau" accept="image/*"
                                               class="form-control-file <?php $__errorArgs = ['cmt_mat_sau'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="cmt_sau" disabled>
                                        <label for="cmt_sau">Mặt sau</label><br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-3 col-sm-4 control-label">Email nhân viên<span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="email" id="email" class="form-control" value="<?php if(isset($request['email'])): ?>  <?php echo e($request['email']); ?> <?php else: ?> <?php echo e($objItem->email); ?> <?php endif; ?>" <?php if($objItem->email  != ''): ?>  <?php endif; ?> disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="id_phong_ban" class="col-md-3 col-sm-4 control-label">Phòng Ban</label>
                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="email" id="email" class="form-control" value="<?php echo e($objPhongBan->ten_phong_ban); ?>"disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_chuc_vu" class="col-md-3 col-sm-4 control-label">Chức Vụ</label>
                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="email" id="email" class="form-control" value="<?php echo e($objChucVu->ten_chuc_vu); ?>"disabled>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>

        </form>

    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('default/plugins/input-mask/jquery.inputmask.js')); ?>"></script>
    <script src="<?php echo e(asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js')); ?>"></script>
    
    <script src="<?php echo e(asset('js/nhanvien.js')); ?>"></script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('templates.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Download\QuanLyNhanSu\QuanLyNhanSu\resources\views/nhanvien/fr-chi-tiet-thong-tin-nhan-vien.blade.php ENDPATH**/ ?>