<?php 
include 'core/init.php';
protect_page();
admin_protect(); 

include 'includes/overall/header.php';?>


<main>
<h1>Admin</h1>
<p>Admin Page</p>
</main> 

<input type="text" class="autosuggest" placeholder="search here...">
<input type="button" name="submit" value="search">
<div class="dropdown">
	<ul class="result">
		<li data-id="269">mannnnnni</li>
	</ul>
</div>

<script src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="js/primary.js"></script>
<?php include 'includes/overall/footer.php';?>