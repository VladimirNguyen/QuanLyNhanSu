<?php $__env->startSection('title', 'Xếp Ca'); ?>
<?php $__env->startSection('css'); ?>
<style>
    .badge-navy  { background-color: #1B2A4A; color:#fff; padding:3px 8px; border-radius:4px; font-size:13px; }
    .badge-mint   { background-color: #A8D8C8; color:#1C1C2E; padding:3px 8px; border-radius:4px; font-size:13px; }
    .badge-red   { background-color: #C0392B; color:#fff; padding:3px 8px; border-radius:4px; font-size:13px; }
    .badge-grey  { background-color: #B0BEC5; color:#1B2A4A; padding:3px 8px; border-radius:4px; font-size:13px; }
    .filter-bar { background:#f4f6f9; border:1px solid #e0e6ed; border-radius:6px; padding:12px 16px; margin-bottom:16px; }
    .box-header-navy { background:#1B2A4A !important; color:#fff; }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<section class="content-header">
    <h1><i class="fa fa-calendar-check-o"></i> Xếp Ca <small>Duyệt đăng ký & xếp ca thủ công</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo e(url('/user')); ?>"><i class="fa fa-home"></i> Trang chủ</a></li>
        <li class="active">Xếp Ca</li>
    </ol>
</section>

<section class="content">
    
    <?php if(Session::has('success')): ?>
        <div class="alert alert-success alert-dismissible">
            <strong><?php echo e(Session::get('success')); ?></strong>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>
    <?php if(Session::has('error')): ?>
        <div class="alert alert-danger alert-dismissible">
            <strong><?php echo e(Session::get('error')); ?></strong>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger" role="alert">
            <ul style="margin:0;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php
        $fNgay = $params['ngay'] ?? date('Y-m-d');
        $fPhongBan = $params['id_phong_ban'] ?? '';
        $fTuNgay = $params['tu_ngay'] ?? date('Y-m-d');
    ?>

    
    <div class="filter-bar">
        <form method="GET" action="<?php echo e(route('route_Admin_XepCa')); ?>" class="form-inline">
            <label><i class="fa fa-filter"></i> Cửa hàng:</label>
            <select name="id_phong_ban" class="form-control" style="margin:0 10px;">
                <option value="">Tất cả</option>
                <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($st->id); ?>" <?php echo e((string)$fPhongBan === (string)$st->id ? 'selected' : ''); ?>><?php echo e($st->ten_phong_ban); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <label><i class="fa fa-calendar"></i> Ngày (đăng ký chờ duyệt):</label>
            <input type="date" name="ngay" value="<?php echo e($fNgay); ?>" class="form-control" style="margin:0 10px;">
            <label><i class="fa fa-calendar-o"></i> Từ ngày (lịch tuần):</label>
            <input type="date" name="tu_ngay" value="<?php echo e($fTuNgay); ?>" class="form-control" style="margin:0 10px;">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Xem</button>
            <a href="<?php echo e(route('route_Admin_XepCa')); ?>" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Hôm nay</a>
        </form>
    </div>

    
    <div class="box">
        <div class="box-header box-header-navy">
            <h3 class="box-title"><i class="fa fa-inbox"></i> Đăng ký ca chờ duyệt</h3>
        </div>
        <div class="box-body table-responsive">
            <?php if(count($pending) == 0): ?>
                <p class="alert alert-warning">Không có đăng ký chờ duyệt.</p>
            <?php else: ?>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background:#1B2A4A;color:#fff;">
                        <th class="text-center" width="40">STT</th>
                        <th>Nhân viên</th>
                        <th class="text-center">Chức vụ</th>
                        <th class="text-center">Cửa hàng</th>
                        <th class="text-center">Ngày</th>
                        <th class="text-center">Ca</th>
                        <th>Ghi chú</th>
                        <th class="text-center">Công cụ</th>
                    </tr>
                </thead>
                <tbody>
                <?php $stt = 1; ?>
                <?php $__currentLoopData = $pending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($stt++); ?></td>
                        <td><strong><?php echo e($item->ho_ten); ?></strong></td>
                        <td class="text-center"><?php echo e($item->ten_chuc_vu); ?></td>
                        <td class="text-center"><?php echo e($item->ten_phong_ban); ?></td>
                        <td class="text-center"><?php echo e(date('d/m/Y', strtotime($item->ngay_lam))); ?></td>
                        <td class="text-center">
                            <?php if($item->ca == 'sang'): ?>
                                <span class="badge-mint">Sáng</span>
                            <?php else: ?>
                                <span class="badge-grey">Chiều</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($item->ghi_chu ?: '—'); ?></td>
                        <td class="text-center" style="white-space:nowrap;">
                            <form method="POST" action="<?php echo e(route('route_Admin_DuyetCa', ['id' => $item->id])); ?>" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="trang_thai" value="2">
                                <input type="hidden" name="ngay" value="<?php echo e($fNgay); ?>">
                                <input type="hidden" name="id_phong_ban" value="<?php echo e($fPhongBan); ?>">
                                <input type="hidden" name="tu_ngay" value="<?php echo e($fTuNgay); ?>">
                                <button type="submit" class="btn btn-xs" style="background:#1B2A4A;color:#fff;border-color:#1B2A4A;">
                                    <i class="fa fa-check"></i> Duyệt
                                </button>
                            </form>
                            <form method="POST" action="<?php echo e(route('route_Admin_DuyetCa', ['id' => $item->id])); ?>" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="trang_thai" value="3">
                                <input type="hidden" name="ngay" value="<?php echo e($fNgay); ?>">
                                <input type="hidden" name="id_phong_ban" value="<?php echo e($fPhongBan); ?>">
                                <input type="hidden" name="tu_ngay" value="<?php echo e($fTuNgay); ?>">
                                <button type="submit" class="btn btn-xs" style="background:#C0392B;color:#fff;border-color:#C0392B;"
                                        onclick="return confirm('Từ chối đăng ký ca này?')">
                                    <i class="fa fa-times"></i> Từ chối
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="box">
        <div class="box-header box-header-navy">
            <h3 class="box-title"><i class="fa fa-plus-circle"></i> Xếp ca thủ công</h3>
        </div>
        <div class="box-body">
            <form method="POST" action="<?php echo e(route('route_Admin_ThemLichCa')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="tu_ngay" value="<?php echo e($fTuNgay); ?>">
                <div class="row">
                    <div class="col-md-5 form-group">
                        <label>Nhân viên <span style="color:#C0392B;">*</span></label>
                        <select name="id_nhan_vien" class="form-control" required>
                            <option value="">-- Chọn nhân viên --</option>
                            <?php $grouped = $employees->groupBy('pb_id'); ?>
                            <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pbId => $emps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <optgroup label="<?php echo e($emps->first()->ten_phong_ban); ?>">
                                    <?php $__currentLoopData = $emps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($emp->id); ?>" <?php echo e((string)old('id_nhan_vien') === (string)$emp->id ? 'selected' : ''); ?>>
                                            <?php echo e($emp->ho_ten); ?> — <?php echo e($emp->ten_phong_ban); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </optgroup>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Ngày làm <span style="color:#C0392B;">*</span></label>
                        <input type="date" name="ngay_lam" class="form-control" value="<?php echo e(old('ngay_lam', date('Y-m-d'))); ?>" required>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Ca <span style="color:#C0392B;">*</span></label>
                        <select name="ca" class="form-control" required>
                            <option value="">-- Ca --</option>
                            <option value="sang" <?php echo e(old('ca') == 'sang' ? 'selected' : ''); ?>>Sáng (9:00 - 15:30)</option>
                            <option value="chieu" <?php echo e(old('ca') == 'chieu' ? 'selected' : ''); ?>>Chiều (15:30 - 22:00)</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary form-control" style="background:#1B2A4A;border-color:#1B2A4A;">
                            <i class="fa fa-plus"></i> Thêm vào lịch
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="box">
        <div class="box-header box-header-navy">
            <h3 class="box-title"><i class="fa fa-calendar"></i> Lịch ca tổng hợp (tất cả cửa hàng)</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered">
                <thead>
                    <tr style="background:#1B2A4A;color:#fff;">
                        <th class="text-center" style="width:16%;">Ngày</th>
                        <th class="text-center">Ca sáng (9:00 - 15:30)</th>
                        <th class="text-center">Ca chiều (15:30 - 22:00)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $thu = ['Chủ Nhật','Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7'];
                    ?>
                    <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $thuIdx = (int) date('w', strtotime($d)); ?>
                        <tr>
                            <td class="text-center" style="background:#1B2A4A;color:#fff;font-weight:600;">
                                <?php echo e($thu[$thuIdx]); ?><br><?php echo e(date('d/m/Y', strtotime($d))); ?>

                            </td>
                            <td style="background:#e8f4f0;">
                                <?php if(!empty($grid[$d]['sang'])): ?>
                                    <ul style="margin:0;padding-left:18px;">
                                        <?php $__currentLoopData = $grid[$d]['sang']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <?php echo e($entry->label); ?>

                                                <a href="<?php echo e(route('route_Admin_XoaLichCa', ['id' => $entry->id])); ?>?tu_ngay=<?php echo e($start); ?>"
                                                   class="text-danger" style="margin-left:6px;"
                                                   onclick="return confirm('Xóa lịch ca này?')">
                                                    <i class="fa fa-trash"></i> Xóa
                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                <?php else: ?>
                                    <span style="color:#999;">—</span>
                                <?php endif; ?>
                            </td>
                            <td style="background:#f0f2f5;">
                                <?php if(!empty($grid[$d]['chieu'])): ?>
                                    <ul style="margin:0;padding-left:18px;">
                                        <?php $__currentLoopData = $grid[$d]['chieu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <?php echo e($entry->label); ?>

                                                <a href="<?php echo e(route('route_Admin_XoaLichCa', ['id' => $entry->id])); ?>?tu_ngay=<?php echo e($start); ?>"
                                                   class="text-danger" style="margin-left:6px;"
                                                   onclick="return confirm('Xóa lịch ca này?')">
                                                    <i class="fa fa-trash"></i> Xóa
                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                <?php else: ?>
                                    <span style="color:#999;">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('templates.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Download\QuanLyNhanSu\QuanLyNhanSu\resources\views/nhanvien/admin-xep-ca.blade.php ENDPATH**/ ?>