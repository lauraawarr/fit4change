<?php session_start(); ?>
<?php

		session_destroy();
		header('Location: index.php');
		exit(0);

?>
<script>
	// var code = window.btoa('227WP9:0f947f3eed699edb1fa68a2c6d45a036'); //Bae64 encode string
 //    fetch(
 //        'https://api.fitbit.com/oauth/revoke',
 //        {
 //            headers: new Headers({
 //                'Authorization': 'Basic ' + code
 //            }),
 //            mode: 'cors',
 //            method: 'POST'
 //        });
 //    console.log('test')
</script>

