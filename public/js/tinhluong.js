$(document).ready(function () {
$('.daterangepicker-click').daterangepicker({

    minDate:moment().subtract(31, 'days'),
    autoUpdateInput: false,
    autoApply:false,
    locale: {
        format: 'D/M/Y',
        cancelLabel: 'Clear',

    }
}, function (start, end, label) {
    // let d = start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY');
    // that[name_data].thoi_gian = d;
    // this.startDate = start;
    // this.endDate = end;
    // $(this).val(d);
    // console.log( this);
});
$('.daterangepicker-click').on('apply.daterangepicker', function (ev, picker) {
    let d = picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY');
    // that[name_data].thoi_gian = d;
    $(this).val(d);
});

$('.daterangepicker-click').on('cancel.daterangepicker', function (ev, picker) {
    // that[name_data].thoi_gian = '';
    $(this).val('');
});
});