    </div>
    <!-- /#wrapper -->

	<? if ( !defined('DEVMODE') ) : ?>
    <script src="js/vendor.js"></script>
	<? else : ?>
	<?php include_once("footer_js.php"); ?>
	<? endif; ?>


	<? @include_once( (dirname(__DIR__)) . "/page_editor/components/new_thread_js.php" ); ?>
    <script src="js/<?=basename($_SERVER['PHP_SELF'], ".php")?>.js<?='?'.time()?>"></script> <!-- Ensure reloading the newest javascript every time -->

</body>

</html>
