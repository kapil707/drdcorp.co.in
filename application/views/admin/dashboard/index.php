	<?= $this->session->keep_flashdata('message'); ?>
	<?php /*
	if($this->session->userdata('user_type')!="") { ?>
		<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-lg-6">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>Top 10 Sales Medicine Today</h5>
						</div>
						<div class="ibox-content">
							<div id="morris-bar-chart"></div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>Top 10 Sales Medicine Today</h5>
						</div>
						<div class="ibox-content">
							<div id="morris-bar-chart1"></div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Medicine</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_medicine"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Medicine</small>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Chemist</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_chemist"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Chemist</small>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Salesman</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_salesman"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Salesman</small>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Rider</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_rider"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Rider</small>
						</div>
					</div>
				</div>


				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>UserCart</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_cart"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Cart</small>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Unique Orders</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_unique_order"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Unique Orders</small>
						</div>
					</div>
				</div>


				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Download Order</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_order_download"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Download Order</small>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Today Orders</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_order"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Orders</small>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Order Price</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_order_amount"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Order Price</small>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Order Items</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_order_items"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Order Items</small>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Website/Mobile Order</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_pc_mobile_order"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Website Order</small>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Android Order</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_android_order"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Android Order</small>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Invoice</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_invoices"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Invoice</small>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Sales</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="total_invoices_amount"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Sales</small>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Active User Now</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="active_user_count"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Active User Now</small>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<span class="label label-success pull-right">Now</span>
							<h5>Today Active User</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">
								<span id="today_active_user_count"></span>
							</h1>
							<div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
							<small>Total Today Active User</small>
						</div>
					</div>
				</div>
			</div>

            <div class="row">
				<div class="col-xs-12">
					<!-- <a href="" data-toggle="modal" data-target="#myModal_chemist_open_page">
						<span>View Chemist Open Page</span>
					</a>
					<br><br>
					<a href="" data-toggle="modal" data-target="#myModal_chemist_top_search">
						<span>View Chemist Top Search</span>
					</a>
					<div class="wrapper wrapper-content animated fadeInRight">
						<div class="row">
							<div class="col-lg-4">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5>Top 10 Medicine</h5>								
									</div>
									<canvas height="140vh" width="180vw" id="barchart1"></canvas>
								</div>
							</div>
						</div>
					</div> -->

					<div class="ibox float-e-margins">						
						<div class="ibox-title">
							<h5>Online Users</h5>
						</div>
                    	<div class="ibox-content">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="view_api_table">
									<thead>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div><!-- /.row -->
				<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
		</div>
	<?php } */?>
	
	<div id="myModal_online_user" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Active User Now</h4>
			</div>
			<div class="modal-body">
				<table class='table table-striped table-bordered table-hover dataTables-example'>
					<thead>
							<tr>
								<th>Sno.</th>
								<th>Chemist Code</th>
								<th>Active Time</th>
							</tr>
					</thead>
					<tbody class="total_online_users_data">
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			</div>

		</div>
	</div>

	<div id="myModal_today_online_user" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Today Active User Now</h4>
			</div>
			<div class="modal-body">
				<table class='table table-striped table-bordered table-hover dataTables-example'>
					<thead>
							<tr>
								<th>Sno.</th>
								<th>Chemist Code</th>
								<th>Active Time</th>
							</tr>
					</thead>
					<tbody class="today_total_online_users_data">
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			</div>

		</div>
	</div>
	
	<div id="myModal_chemist_open_page" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Chemist activites</h4>
			</div>
			<div class="modal-body">
				<input type="text" class="chemist_id1" style="text-transform: uppercase;">
				<input type="submit" onclick="chemist_open_page()" value="Submit">
				<table class='table table-striped table-bordered table-hover dataTables-example'>
					<thead>
							<tr>
								<th>Sno.</th>
								<th>Chemist Code</th>
								<th>Active Time</th>
								<th>Page Url</th>
							</tr>
					</thead>
					<tbody class="chemist_open_page_dt">
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			</div>

		</div>
	</div>

	<div id="myModal_chemist_top_search" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Chemist Top Search activites</h4>
			</div>
			<div class="modal-body">
				<input type="text" class="chemist_id2" style="text-transform: uppercase;">
				<input type="submit" onclick="chemist_top_search()" value="Submit">
				<table class='table table-striped table-bordered table-hover dataTables-example'>
					<thead>
							<tr>
								<th>Sno.</th>
								<th>Chemist Code</th>
								<th>Active Time</th>
								<th>Item Name</th>
							</tr>
					</thead>
					<tbody class="chemist_top_search_dt">
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			</div>

		</div>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.3.1/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.3.1/firebase-database.js"></script>
    <script>
	

	// Your web app's Firebase configuration
	// For Firebase JS SDK v7.20.0 and later, measurementId is optional
	var  firebaseConfig = {
		apiKey: "AIzaSyBPkM-zLmMQbHGE_Ye1qOsBl6IhROvu6RU",
		authDomain: "drd-noti-fire-base.appspot.com",
		databaseURL: "https://drd-noti-fire-base.firebaseio.com",
		projectId: "drd-noti-fire-base",
		storageBucket: "drd-web-firebase-db.appspot.com",
		messagingSenderId: "504935735685",
		appId: "1:504935735685:android:a2d0ae89504ba935f5e4ec"
	};

  	// Initialize Firebase
  	firebase.initializeApp(firebaseConfig);

	// Set database variable
	var database = firebase.database()

	function total_online_users()
	{
		var leadsRef = database.ref('chemist_online/');
		leadsRef.on('value', function(snapshot) {
			$(".total_online_users").html('');
			$(".total_online_users_data").html("")
			var i = 0;
			snapshot.forEach(function(childSnapshot) {
				var childData = childSnapshot.val();
				console.log(childData.user_time);
			  
				var todayDateTime = new Date().toLocaleString("en-US", {timeZone: "Asia/Kolkata"});
				var m = new Date(todayDateTime)

				var dateString = m.getFullYear() +"-"+ (m.getMonth()+1) +"-"+ m.getDate();
				var timeString = m.getHours() + ":" + m.getMinutes();
			  
				if(timeString==childData.user_time){
					i = parseInt(i) + 1;
					$(".total_online_users_data").append("<tr><td>"+i+"</td><td>"+childData.user_altercode+"</td><td>"+childData.user_time+"</td></tr>");
				}
				$(".total_online_users").html(i);
			});
		});
	}

	function today_total_online_users()
	{
		var leadsRef = database.ref('chemist_online/');
		leadsRef.on('value', function(snapshot) {
			$(".today_total_online_users").html('');
			$(".today_total_online_users_data").html("")
			var i = 0;
			snapshot.forEach(function(childSnapshot) {
				var childData = childSnapshot.val();
				console.log(childData.user_time);
			  
				var todayDateTime = new Date().toLocaleString("en-US", {timeZone: "Asia/Kolkata"});
				var m = new Date(todayDateTime)

				var dateString = m.getFullYear() +"-"+ (m.getMonth()+1) +"-"+ m.getDate();
				var timeString = m.getHours() + ":" + m.getMinutes();
			  
				if(dateString==childData.user_date){
					i = parseInt(i) + 1;
					$(".today_total_online_users_data").append("<tr><td>"+i+"</td><td>"+childData.user_altercode+"</td><td>"+childData.user_time+"</td></tr>");
				}
				$(".today_total_online_users").html(i);
			});
		});
	}

	total_online_users()
	today_total_online_users()
	
	function chemist_open_page()
	{
		var todayDateTime = new Date().toLocaleString("en-US", {timeZone: "Asia/Kolkata"});
		var m 	= new Date(todayDateTime)
		year 	= m.getFullYear()
		month 	= change_dt_format(m.getMonth()+1);
		day 	= change_dt_format(m.getDate());
		hours 	= change_dt_format(m.getHours());
		minutes = change_dt_format(m.getMinutes());
		var dateString = year +"-"+ (month) +"-"+ day;
		var timeString = hours + ":" + minutes;

		chemist_id1 = $(".chemist_id1").val()
		chemist_id1 = chemist_id1.toUpperCase();
		var leadsRef = database.ref('chemist_open_page/'+dateString+'/'+chemist_id1);
		leadsRef.on('value', function(snapshot) {
			$(".chemist_open_page_dt").html("")
			var i = 0;
			snapshot.forEach(function(childSnapshot) {
				var childData = childSnapshot.val();
				
				i = parseInt(i) + 1;
				$(".chemist_open_page_dt").append("<tr><td>"+i+"</td><td>"+childData.user_altercode+"</td><td>"+childData.user_time+"</td><td>"+childData.page_url+"</td></tr>");
			});
		});
	}

	function chemist_top_search()
	{
		var todayDateTime = new Date().toLocaleString("en-US", {timeZone: "Asia/Kolkata"});
		var m 	= new Date(todayDateTime)
		year 	= m.getFullYear()
		month 	= change_dt_format(m.getMonth()+1);
		day 	= change_dt_format(m.getDate());
		hours 	= change_dt_format(m.getHours());
		minutes = change_dt_format(m.getMinutes());
		var dateString = year +"-"+ (month) +"-"+ day;
		var timeString = hours + ":" + minutes;

		chemist_id2 = $(".chemist_id2").val()
		chemist_id2 = chemist_id2.toUpperCase();
		var leadsRef = database.ref('chemist_top_search/'+dateString+'/'+chemist_id2);
		leadsRef.on('value', function(snapshot) {
			$(".chemist_top_search_dt").html("")
			var i = 0;
			snapshot.forEach(function(childSnapshot) {
				var childData = childSnapshot.val();
				
				i = parseInt(i) + 1;
				$(".chemist_top_search_dt").append("<tr><td>"+i+"</td><td>"+childData.user_altercode+"</td><td>"+childData.user_time+"</td><td>"+childData.item_name+" | " + childData.item_packing +"</td></tr>");
			});
		});
	}
	function change_dt_format(dt)
	{
		if(dt==1)
		{
			dt = "01"
		}
		if(dt==2)
		{
			dt = "02"
		}
		if(dt==3)
		{
			dt = "03"
		}
		if(dt==4)
		{
			dt = "04"
		}
		if(dt==5)
		{
			dt = "05"
		}
		if(dt==6)
		{
			dt = "06"
		}
		if(dt==7)
		{
			dt = "07"
		}
		if(dt==8)
		{
			dt = "08"
		}
		if(dt==9)
		{
			dt = "09"
		}
		return dt;
	}
</script>

<script>
function fetchDashboardData() {
	$.ajax({
		url: '<?php echo base_url(); ?>admin/<?php echo $Page_name ?>/get_data',
		type: 'GET',
		dataType: 'json',
		success: function(response) {
			$('#total_medicine').text(response.total_medicine);
			$('#total_chemist').text(response.total_chemist);
			$('#total_salesman').text(response.total_salesman);
			$('#total_rider').text(response.total_rider);
			$('#total_cart').text(response.total_cart);
			$('#total_unique_order').text(response.total_unique_order);
			$('#total_order').text(response.total_order);
			$('#total_order_download').text(response.total_order_download);
			$('#total_order_amount').text(response.total_order_amount);
			$('#total_order_items').text(response.total_order_items);
			$('#total_pc_mobile_order').text(response.total_pc_mobile_order);
			$('#total_android_order').text(response.total_android_order);
			$('#total_invoices').text(response.total_invoices);
			$('#total_invoices_amount').text(response.total_invoices_amount);
			$('#active_user_count').text(response.active_user_count);
			$('#today_active_user_count').text(response.today_active_user_count);
		},
		error: function() {
			console.error('Failed to fetch active user count');
		}
	});
	setInterval(function () {
		fetchDashboardData();
	}, 25000);
}
$(document).ready(function() {
	fetchDashboardData();
});
</script>