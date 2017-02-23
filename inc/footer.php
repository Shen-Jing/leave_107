    </div>
    <!-- /#wrapper -->


    <script src="js/core.js"></script>

    <script src="js/vendor.js"></script>

    <!-- bootstrap validator -->
    <script src="bower_components/bootstrapvalidator/dist/js/bootstrapValidator.min.js"></script>

	<? @include_once( (dirname(__DIR__)) . "/page_editor/components/new_thread_js.php" ); ?>
    <script src="js/<?=basename($_SERVER['PHP_SELF'], ".php")?>.js<?='?'.time()?>"></script> <!-- Ensure reloading the newest javascript every time -->

</body>

</html>
