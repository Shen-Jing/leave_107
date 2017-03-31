'use strict';

var gulp = require('gulp'),
    concat = require('gulp-concat'),
    minifyCSS = require('gulp-minify-css'),
    uglify = require('gulp-uglify');


const vendorJs = [
//core
	'bower_components/jquery/dist/jquery.min.js',
    'bower_components/bootstrap/dist/js/bootstrap.min.js',
    'bower_components/metisMenu/dist/metisMenu.min.js',
    'bower_components/moment/min/moment-with-locales.min.js',
	'src/app/js/bootstrap_message.js',
	'src/app/js/sb-admin-2.js',
	'src/app/js/sbadmin2-sidebar-toggle.js',
//vendor
    'bower_components/toastr/toastr.min.js',
    'bower_components/bootstrap-select/dist/js/bootstrap-select.min.js',
    'bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
    'bower_components/bootbox.js/bootbox.min.js',
    'bower_components/jquery-sortable/source/js/jquery-sortable-min.js',
	'bower_components/bootstrapvalidator/dist/js/bootstrapValidator.min.js',
    'src/custom/js/jquery.dataTables.min.js',
	'src/custom/js/dataTables.responsive.min.js',
	'src/custom/js/dataTables.bootstrap.min.js',
	'src/custom/js/dataTables.buttons.min.js',
	'src/custom/js/jszip.min.js',
	'src/custom/js/grid.locale-tw.js',
	'src/custom/js/jquery.jqGrid.min.js',
	'src/custom/js/plugin.buttons.html5.min.js',
	'src/custom/js/plugin.buttons.print.min.js'
];

const vendorCss = [
//core
	'bower_components/bootstrap/dist/css/bootstrap.min.css',
    'bower_components/metisMenu/dist/metisMenu.min.css',
    'src/app/css/sb-admin-2.css',
	'src/app/css/sbadmin2-sidebar-toggle.css',
	'src/app/css/ncue.css',
	'src/app/css/timeline.css',
	'src/app/css/jquery-ui.css',
//vendor
    'bower_components/font-awesome/css/font-awesome.min.css',
    'bower_components/toastr/toastr.min.css',
    'bower_components/bootstrap-select/dist/css/bootstrap-select.min.css',
    'bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
	'bower_components/bootstrapvalidator/dist/css/bootstrapValidator.min.css',
    'src/custom/css/responsive.dataTables.min.css',
	'src/custom/css/dataTables.bootstrap.min.css',
	'src/custom/css/buttons.dataTables.min.css',
	'src/custom/css/dataTables_sorticon.css',
	'src/custom/css/ui.jqgrid-bootstrap.css'
];


gulp.task('dist', ['vendor:js', 'vendor:css']);


gulp.task('vendor:js', function() {
    return gulp.src(vendorJs)
        .pipe(concat('vendor.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('dist/js'));
});

gulp.task('vendor:css', function() {
    return gulp.src(vendorCss)
        .pipe(concat('vendor.css'))
        //.pipe(minifyCSS())
        .pipe(gulp.dest('dist/css'));
});