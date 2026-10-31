<?php $__env->startSection('title', '1233'); ?>
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
                            <input type="text" name="search_ten_chuc_vu" class="form-control" placeholder="Tên chức vụ"
                                   value="<?php if(isset($extParams['search_ten_chuc_vu'])): ?><?php echo e($extParams['search_ten_chuc_vu']); ?><?php endif; ?>">
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="text-align:center;">
                        <div class="form-group">
                            <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i
                                        class="fa fa-search" style="color:white;"></i> Search
                            </button>
                            <a href="<?php echo e(url('/chucvu-list')); ?>" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                                Clear </a>
                            <a href="<?php echo e(route('route_BackEnd_ChucVU_Add')); ?>" class="btn btn-info btn-sm"><i class="fa fa-user-plus" style="color:white;"></i>
                                Add new</a>
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
                            <th class="text-center">Tên chức vụ</th>
                            <th class="text-center">Trạng thái</th>
                            <th width="50px" class="text-center">Công cụ</th>
                        </tr>
                        <?php ($i=1); ?>
                        <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <tr>
                                
                                <td class="text-center"><?php echo e($i++); ?></td>
                                <td class="text-center"><?php echo e($item->ten_chuc_vu); ?></td>
                                <td class="text-center" style="width: 180px;background-color:
                                <?php if($item->trang_thai == 0): ?>
                                        red
                                <?php else: ?>
                                        green
                                <?php endif; ?>;
                                        color: white">
                                    <?php if($item->trang_thai == 0): ?>
                                        Tạm Dừng Hoạt Động
                                    <?php else: ?>
                                        Hoạt Động
                                    <?php endif; ?>
                                </td>
                                </td>
                                <td class="text-center"><a href="<?php echo e(route('route_BackEnd_ChucVu_Detail',['id'=> $item->id])); ?>" title="Sửa"><i class="fa fa-edit"></i></a></td>
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



<?php echo $__env->make('templates.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Download\QuanLyNhanSu\QuanLyNhanSu\resources\views/chucvu/chuc-vu-list.blade.php ENDPATH**/ ?>