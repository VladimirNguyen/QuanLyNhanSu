
<?php $__env->startSection('title', '1233'); ?>
<?php $__env->startSection('script'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('default/bower_components/select2/dist/css/select2.min.css')); ?> ">
    <link rel="stylesheet" href="<?php echo e(asset('default/bower_components/bootstrap-daterangepicker/daterangepicker.css')); ?> ">
    <?php $__env->startSection('css'); ?>
        <style>
            body {
                /*-webkit-touch-callout: none;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                -o-user-select: none;*/
                user-select: none;
            }

            .toolbar-box form .btn {
                /*margin-top: -3px!important;*/
            }

            .select2-container {
                margin-top: 0;
            }

            .select2-container--default .select2-selection--multiple {
                border-radius: 0;
            }

            .select2-container .select2-selection--multiple {
                min-height: 30px;
            }

            .select2-container .select2-search--inline .select2-search__field {
                margin-top: 3px;
            }

            .table > tbody > tr.success > td {
                background-color: #009688;
                color: white !important;
            }

            .table > tbody > tr.success > td span {
                color: white !important;
            }

            .table > tbody > tr.success > td span.button__csentity {
                color: #333 !important;
            }

            /*.table>tbody>tr.success>td i{*/
            /*    color: white !important;*/
            /*}*/
            .text-silver {
                color: #f4f4f4;
            }

            .btn-silver {
                background-color: #f4f4f4;
                color: #333;
            }

            .select2-container--default .select2-results__group {
                background-color: #eeeeee;
            }
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

            .select2-container--default .select2-selection--multiple {
                margin-top: 10px;
                border-radius: 0;
            }

            .select2-container--default .select2-results__group {
                background-color: #eeeeee;
            }
        </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <?php echo $__env->make('templates.header-action', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="clearfix"></div>
        <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
            <form action="" method="get">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <input type="text" name="search_ngay_tinh_luong" class="form-control daterangepicker-click" placeholder="Chọn ngày"
                                   value="<?php if(isset($extParams['search_ngay_tinh_luong'])): ?><?php echo e($extParams['search_ngay_tinh_luong']); ?><?php endif; ?>" autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="text-align:center;">
                        <div class="form-group">
                            <button type="submit" name="btnSearch" class="btn btn-primary btn-sm"><i class="fa fa-search" style="color:white;"></i> Search</button>
                            <a href="<?php echo e(route('route_BackEnd_CongNhanVien_List',['id'=>request()->route('id')])); ?>" class="btn btn-default btn-sm"><i class="fa fa-remove"></i> Clear</a>
                        </div>
                    </div>
                    
                    <?php
                        $now = date('Y-m-d');
                        $idNV = request()->route('id');
                        $chamHomNay = \Illuminate\Support\Facades\DB::table('cham_cong')
                            ->where('id_nhan_vien', $idNV)
                            ->where('ngay_lam', $now)
                            ->first();
                    ?>
                    <div class="col-xs-12" style="margin-top:10px;">
                        <div style="background:#f4f6f9;border:2px solid #1B2A4A;border-radius:8px;padding:16px;text-align:center;">
                            <p style="font-size:15px;font-weight:600;color:#1B2A4A;margin-bottom:12px;">
                                <i class="fa fa-clock-o"></i> Chấm công hôm nay: <strong><?php echo e(date('d/m/Y')); ?></strong>
                                &nbsp;|&nbsp; <span id="dongho" style="color:#C0392B;font-size:16px;font-weight:700;"></span>
                            </p>
                            <?php if(!$chamHomNay): ?>
                                <form method="POST" action="<?php echo e(route('route_BackEnd_CongNhanVien_Add', ['id'=>$idNV])); ?>" style="display:inline"><?php echo csrf_field(); ?><button type="submit"  
                                   class="btn btn-lg"
                                   style="background:#1B2A4A;color:#fff;font-weight:700;padding:10px 30px;border-radius:6px;"
                                   onclick="return confirm('Xac nhan cham vao ca?')">
                                    <i class="fa fa-sign-in"></i> Cham Vao Ca
                                </button></form>
                            <?php elseif($chamHomNay && ($chamHomNay->gio_ra == '00:00:00' || !$chamHomNay->gio_ra)): ?>
                                <span style="background:#e8f4f0;color:#1B2A4A;padding:8px 16px;border-radius:6px;font-weight:600;margin-right:12px;">
                                    <i class="fa fa-check-circle" style="color:#7EC8B0;"></i>
                                    Da vao ca luc: <strong><?php echo e(date('H:i', strtotime($chamHomNay->gio_vao))); ?></strong>
                                </span>
                                <form method="POST" action="<?php echo e(route('route_BackEnd_CongNhanVien_Update', ['id'=>$chamHomNay->id])); ?>" style="display:inline"><?php echo csrf_field(); ?><button type="submit"  
                                   class="btn btn-lg"
                                   style="background:#C0392B;color:#fff;font-weight:700;padding:10px 30px;border-radius:6px;"
                                   onclick="return confirm('Xac nhan ket ca?')">
                                    <i class="fa fa-sign-out"></i> Ket Ca
                                </button></form>
                            <?php else: ?>
                                <span style="background:#e8f4f0;color:#1B2A4A;padding:8px 16px;border-radius:6px;font-weight:600;">
                                    <i class="fa fa-check-circle" style="color:#7EC8B0;"></i>
                                    Vao ca: <strong><?php echo e(date('H:i', strtotime($chamHomNay->gio_vao))); ?></strong>
                                    &nbsp;&nbsp;
                                    <i class="fa fa-sign-out" style="color:#C0392B;"></i>
                                    Ket ca: <strong><?php echo e(date('H:i', strtotime($chamHomNay->gio_ra))); ?></strong>
                                    <?php $phut = (strtotime($chamHomNay->gio_ra) - strtotime($chamHomNay->gio_vao)) / 60; ?>
                                    &nbsp; <i class="fa fa-clock-o"></i> <?php echo e(floor($phut/60)); ?>h <?php echo e($phut%60); ?>p
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </form>
            <div class="clearfix"></div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content appTuyenSinh">
        <div id="msg-box">
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
        </div>
        <?php if(count($list)<=0): ?>
            <p class="alert alert-warning">
                Không có dữ liệu phù hợp
            </p>
        <?php endif; ?>
        <div class="box-body table-responsive no-padding">
            <form action="" method="post">
                <?php echo csrf_field(); ?>
                <span class="pull-right">Tổng số bản ghi tìm thấy: <span
                            style="font-size: 15px;font-weight: bold;"><?php echo e($list->count()); ?></span></span>
                <div class="clearfix"></div>
                <div class="double-scroll">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 50px" class="text-center">
                                STT
                            </th>
                            <th class="text-center">Ngày làm</th>
                            <th class="text-center">Giờ vào</th>
                            <th class="text-center">Giờ ra</th>
                            <th class="text-center">Trạng thái</th>
                            <th width="50px" class="text-center">Công Cụ</th>
                        </tr>
                        <?php ($id=1); ?>

                        <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <tr>
                                <td class="text-center"><?php echo e($id++); ?></td>
                                <td class="text-center"><?php echo e(date("d/m/Y", strtotime($item->ngay_lam))); ?></td>
                                <td class="text-center"><?php echo e($item->gio_vao); ?></td>
                                <td class="text-center"><?php echo e($item->gio_ra); ?></td>
                                <td class="text-center" width="100px" style="background-color:
                                <?php if($item->trang_thai == 0): ?>
                                        red
                                <?php else: ?>
                                        green
                                <?php endif; ?>;
                                        color: white">
                                    <?php if($item->trang_thai == 0): ?>
                                        Không đi làm
                                    <?php else: ?>
                                        Có đi làm
                                    <?php endif; ?>
                                </td>

                                <?php if($item->gio_ra >0): ?>
                                    <td class="text-center"></td>
                                <?php else: ?>
                                    <td class="text-center"><form method="POST" action="<?php echo e(route('route_BackEnd_CongNhanVien_Update',['id'=> $item->id])); ?>" style="display:inline"><?php echo csrf_field(); ?><button type="submit"   title="Chấm công ra về"><i class="fa fa-edit"></i></button></form></td>
                                <?php endif; ?>

                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </table>
                </div>
            </form>
        </div>
        <br>
        <div class="text-center">
            <?php echo e($list->appends($extParams)->links()); ?>

        </div>
        <index-cs ref="index_cs"></index-cs>
    </section>

<?php $__env->stopSection(); ?>
    <?php $__env->startSection('script'); ?>
        <script src="<?php echo e(asset('default/plugins/input-mask/jquery.inputmask.js')); ?>"></script>
        <script src="<?php echo e(asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js')); ?>"></script>
        <script src="<?php echo e(asset('default/bower_components/moment/min/moment.min.js')); ?>"></script>
        <script src="<?php echo e(asset('default/bower_components/bootstrap-daterangepicker/daterangepicker.js')); ?>"></script>
        <script src="<?php echo e(asset('js/tinhluong.js')); ?> "></script>
        <script>
            // Đồng hồ realtime
            function capNhatDongHo() {
                var now = new Date();
                var h = String(now.getHours()).padStart(2,'0');
                var m = String(now.getMinutes()).padStart(2,'0');
                var s = String(now.getSeconds()).padStart(2,'0');
                var el = document.getElementById('dongho');
                if (el) el.textContent = h + ':' + m + ':' + s;
            }
            capNhatDongHo();
            setInterval(capNhatDongHo, 1000);
        </script>
    <?php $__env->stopSection(); ?>


<?php echo $__env->make('templates.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Download\QuanLyNhanSu\QuanLyNhanSu\resources\views/nhanvien/cham-cong-nhan-vien.blade.php ENDPATH**/ ?>