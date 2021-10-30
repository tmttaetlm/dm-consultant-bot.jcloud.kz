<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
	<ul id="slide-out" class="side-nav fixed z-depth-2">
	<li class="center no-padding">
		<div class="indigo darken-2 white-text" style="height: 180px;">
		<div class="row">
			<img style="margin-top: 5%;" width="100" height="100" src="images/logo1.jpg" class="circle responsive-img" />
			<p style="margin-top: -8%; line-height: 24px">Админ-панель бота Деньги-Маркет</p>
		</div>
		</div>
	</li>
	<li id="dash_dashboard"><a class="waves-effect" href="#!"><b>Dashboard</b></a></li>
	<ul class="collapsible" data-collapsible="accordion">
		<li id="dash_users">
		<div id="dash_users_header" class="collapsible-header waves-effect"><b>Users</b></div>
		<div id="dash_users_body" class="collapsible-body">
			<ul>
			<li id="users_seller">
				<a class="waves-effect" style="text-decoration: none;" href="#!">Seller</a>
			</li>

			<li id="users_customer">
				<a class="waves-effect" style="text-decoration: none;" href="#!">Customer</a>
			</li>
			</ul>
		</div>
		</li>

		<li id="dash_products">
		<div id="dash_products_header" class="collapsible-header waves-effect"><b>Products</b></div>
		<div id="dash_products_body" class="collapsible-body">
			<ul>
			<li id="products_product">
				<a class="waves-effect" style="text-decoration: none;" href="#!">Products</a>
				<a class="waves-effect" style="text-decoration: none;" href="#!">Orders</a>
			</li>
			</ul>
		</div>
		</li>

		<li id="dash_categories">
		<div id="dash_categories_header" class="collapsible-header waves-effect"><b>Categories</b></div>
		<div id="dash_categories_body" class="collapsible-body">
			<ul>
			<li id="categories_category">
				<a class="waves-effect" style="text-decoration: none;" href="#!">Category</a>
			</li>

			<li id="categories_sub_category">
				<a class="waves-effect" style="text-decoration: none;" href="#!">Sub Category</a>
			</li>
			</ul>
		</div>
		</li>

		<li id="dash_brands">
		<div id="dash_brands_header" class="collapsible-header waves-effect"><b>Brands</b></div>
		<div id="dash_brands_body" class="collapsible-body">
			<ul>
			<li id="brands_brand">
				<a class="waves-effect" style="text-decoration: none;" href="#!">Brand</a>
			</li>

			<li id="brands_sub_brand">
				<a class="waves-effect" style="text-decoration: none;" href="#!">Sub Brand</a>
			</li>
			</ul>
		</div>
		</li>
	</ul>
	</ul>

	<header>
	<ul class="dropdown-content" id="user_dropdown">
		<li><a class="indigo-text" href="#!">Profile</a></li>
		<li><a class="indigo-text" href="#!">Logout</a></li>
	</ul>

	<nav>
		<div class="nav-wrapper indigo darken-2">
		<a style="margin-left: 20px;" class="breadcrumb" href="#!">Admin</a>
		<a class="breadcrumb" href="#!">Index</a>
		<div style="margin-right: 20px;" id="timestamp" class="right"></div>
		<ul class="right hide-on-med-and-down">
				<li>
				<a class='right dropdown-button' href='' data-activates='user_dropdown'><i class=' material-icons'>account_circle</i></a>
				</li>
			</ul>
			<a href="#" data-activates="slide-out" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
		</div>
	</nav>
	</header>

	<main>
	<div class="row">
		<div class="col s6">
		<div style="padding: 35px;" align="center" class="card">
			<div class="row">
			<div class="left card-title">
				<b>User Management</b>
			</div>
			</div>

			<div class="row">
			<a href="#!">
				<div style="padding: 30px;" class="grey lighten-3 col s5 waves-effect">
				<i class="indigo-text text-lighten-1 large material-icons">person</i>
				<span class="indigo-text text-lighten-1"><h5>Seller</h5></span>
				</div>
			</a>
			<div class="col s1">&nbsp;</div>
			<div class="col s1">&nbsp;</div>

			<a href="#!">
				<div style="padding: 30px;" class="grey lighten-3 col s5 waves-effect">
				<i class="indigo-text text-lighten-1 large material-icons">people</i>
				<span class="indigo-text text-lighten-1"><h5>Customer</h5></span>
				</div>
			</a>
			</div>
		</div>
		</div>

		<div class="col s6">
		<div style="padding: 35px;" align="center" class="card">
			<div class="row">
			<div class="left card-title">
				<b>Product Management</b>
			</div>
			</div>
			<div class="row">
			<a href="#!">
				<div style="padding: 30px;" class="grey lighten-3 col s5 waves-effect">
				<i class="indigo-text text-lighten-1 large material-icons">store</i>
				<span class="indigo-text text-lighten-1"><h5>Product</h5></span>
				</div>
			</a>

			<div class="col s1">&nbsp;</div>
			<div class="col s1">&nbsp;</div>

			<a href="#!">
				<div style="padding: 30px;" class="grey lighten-3 col s5 waves-effect">
				<i class="indigo-text text-lighten-1 large material-icons">assignment</i>
				<span class="indigo-text text-lighten-1"><h5>Orders</h5></span>
				</div>
			</a>
			</div>
		</div>
		</div>
	</div>

	<div class="row">
		<div class="col s6">
		<div style="padding: 35px;" align="center" class="card">
			<div class="row">
			<div class="left card-title">
				<b>Brand Management</b>
			</div>
			</div>

			<div class="row">
			<a href="#!">
				<div style="padding: 30px;" class="grey lighten-3 col s5 waves-effect">
				<i class="indigo-text text-lighten-1 large material-icons">local_offer</i>
				<span class="indigo-text text-lighten-1"><h5>Brand</h5></span>
				</div>
			</a>

			<div class="col s1">&nbsp;</div>
			<div class="col s1">&nbsp;</div>

			<a href="#!">
				<div style="padding: 30px;" class="grey lighten-3 col s5 waves-effect">
				<i class="indigo-text text-lighten-1 large material-icons">loyalty</i>
				<span class="indigo-text text-lighten-1"><h5>Sub Brand</h5></span>
				</div>
			</a>
			</div>
		</div>
		</div>

		<div class="col s6">
		<div style="padding: 35px;" align="center" class="card">
			<div class="row">
			<div class="left card-title">
				<b>Category Management</b>
			</div>
			</div>
			<div class="row">
			<a href="#!">
				<div style="padding: 30px;" class="grey lighten-3 col s5 waves-effect">
				<i class="indigo-text text-lighten-1 large material-icons">view_list</i>
				<span class="indigo-text text-lighten-1"><h5>Category</h5></span>
				</div>
			</a>
			<div class="col s1">&nbsp;</div>
			<div class="col s1">&nbsp;</div>

			<a href="#!">
				<div style="padding: 30px;" class="grey lighten-3 col s5 waves-effect">
				<i class="indigo-text text-lighten-1 large material-icons">view_list</i>
				<span class="truncate indigo-text text-lighten-1"><h5>Sub Category</h5></span>
				</div>
			</a>
			</div>
		</div>
		</div>
	</div>

	<div class="fixed-action-btn click-to-toggle" style="bottom: 45px; right: 24px;">
		<a class="btn-floating btn-large pink waves-effect waves-light">
		<i class="large material-icons">add</i>
		</a>

		<ul>
		<li>
			<a class="btn-floating red"><i class="material-icons">note_add</i></a>
			<a href="" class="btn-floating fab-tip">Add a note</a>
		</li>

		<li>
			<a class="btn-floating yellow darken-1"><i class="material-icons">add_a_photo</i></a>
			<a href="" class="btn-floating fab-tip">Add a photo</a>
		</li>

		<li>
			<a class="btn-floating green"><i class="material-icons">alarm_add</i></a>
			<a href="" class="btn-floating fab-tip">Add an alarm</a>
		</li>

		<li>
			<a class="btn-floating blue"><i class="material-icons">vpn_key</i></a>
			<a href="" class="btn-floating fab-tip">Add a master password</a>
		</li>
		</ul>
	</div>
	</main>

	<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js"></script>  
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('.button-collapse').sideNav();
		$('.collapsible').collapsible();
		$('select').material_select();
		});
	</script>

</body>
</html>