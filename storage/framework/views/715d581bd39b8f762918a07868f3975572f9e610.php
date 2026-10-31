
<?php $__env->startSection('content'); ?>
<section class="content">
<h2>Bảng lương tổng hợp tháng <?php echo e($month); ?></h2>
<form method="GET"><label for="month">Tháng</label> <input id="month" name="month" type="month" value="<?php echo e($month); ?>" required> <button class="btn btn-primary" type="submit">Xem bảng lương</button></form>
<p style="margin-top:15px">Ca bắt đầu <?php echo e(config('payroll.shift_start')); ?>. Vào muộn quá <?php echo e(config('payroll.late_minutes')); ?> phút trừ 0,5 công/ngày. Một ngày có nhiều lần chấm chỉ tính một công theo giờ vào sớm nhất.</p>
<p>Lương tạm tính = công được hưởng × (mức lương tháng / <?php echo e(config('payroll.standard_days')); ?>) + phụ cấp. Phụ cấp cộng một lần/tháng theo phân công đang hoạt động, kể cả tháng chưa có công. Chưa tính tăng ca và các khoản khấu trừ khác.</p>
<p>Bảng dùng mức lương và phụ cấp hiện tại, chưa phải bảng lương đã chốt lịch sử. Chưa có mức lương sẽ hiển thị “Chưa thiết lập”.</p>
<div class="table-responsive"><table class="table table-bordered table-striped">
<thead><tr><th>Nhân viên</th><th>Ngày chấm công</th><th>Ngày muộn quá 1h</th><th>Công bị trừ</th><th>Công được hưởng</th><th>Mức lương tháng</th><th>Đơn giá ngày</th><th>Lương theo công</th><th>Phụ cấp</th><th>Tổng tạm tính (VNĐ)</th></tr></thead>
<tbody><?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr>
<td><?php echo e($row['employee']->ho_ten); ?></td><td><?php echo e($row['days']); ?></td><td><?php echo e($row['late']); ?></td><td><?php echo e($row['late'] * 0.5); ?></td><td><?php echo e($row['units']); ?></td>
<td><?php echo e($row['rate'] ? number_format($row['rate']->luong) : 'Chưa thiết lập'); ?></td>
<td><?php echo e($row['daily'] === null ? '—' : number_format($row['daily'], 2)); ?></td>
<td><?php echo e($row['base'] === null ? '—' : number_format($row['base'])); ?></td><td><?php echo e(number_format($row['allowance'])); ?></td><td><?php echo e($row['total'] === null ? 'Chưa thiết lập' : number_format($row['total'])); ?></td>
</tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="10">Không có nhân viên.</td></tr><?php endif; ?></tbody>
<tfoot><tr><th colspan="7">Tổng (<?php echo e(count(array_filter($rows, function ($r) { return $r['total'] === null; }))); ?> nhân viên chưa có mức lương)</th><th><?php echo e(number_format(array_sum(array_column($rows, 'base')))); ?></th><th><?php echo e(number_format(array_sum(array_column($rows, 'allowance')))); ?></th><th><?php echo e(number_format(array_sum(array_column($rows, 'total')))); ?></th></tr></tfoot>
</table></div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('templates.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Download\QuanLyNhanSu\QuanLyNhanSu\resources\views/luong/tong-hop.blade.php ENDPATH**/ ?>