<h1 class=" text-info">
    <i class="fa fa-caret-right btn-spx-toggle-toolbar" style="cursor: pointer"></i>
    <?php echo e($_title); ?>

    <small>
        <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
            <?php if(isset($routeIndex)): ?>
                <li><a href="<?php echo e(route($routeIndex)); ?>"><?php echo e($routeIndexText); ?></a></li>
            <?php endif; ?>
            <?php if(isset($_action)): ?>
                <li lass="active"><?php echo e($_action); ?></li>
            <?php endif; ?>
        </ol>
    </small>
</h1>
<?php /**PATH D:\Download\QuanLyNhanSu\QuanLyNhanSu\resources\views/templates/header-action.blade.php ENDPATH**/ ?>