<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?= $this->Scheme_Model->get_website_data("title") ;?> | 404 page not found</title>
        
        <link rel="shortcut icon" href="<?= base_url()?>/uploads/manage_website/photo/main/<?= $this->Scheme_Model->get_website_data("icon") ;?>" type="image/x-icon">
        <link rel="icon" href="<?= base_url()?>/uploads/manage_website/photo/main/<?= $this->Scheme_Model->get_website_data("icon") ;?>" type="image/x-icon">
        
	</head>
	<body class="">
		<section class="login-main-wrapper">
		  <div class="main-container">
			  <div class="login-process">
				  <div class="login-main-container">
					  <div class="thankyou-wrapper">
							<h1>
							<img alt="image" class="" src="<?= base_url()?>uploads/manage_website/photo/main/<?= $this->Scheme_Model->get_website_data("logo") ;?>" width="120" /></h1>
							<h1>404 Page Not Found</h1>
							<p>The page you requested could not be found.</p>
							<a href="<?php echo base_url(); ?>">Back to home</a>
							<div class="clr"></div>
						</div>
						<div class="clr"></div>
					</div>
				</div>
				<div class="clr"></div>
			</div>
		</section>
    
    <style>
.thankyou-wrapper{
  width:100%;
  height:auto;
  margin:auto;
  background:#ffffff; 
  padding:00px 0px 50px;
}
.thankyou-wrapper h1{
  font:100px Arial, Helvetica, sans-serif;
  text-align:center;
  color:#333333;
  padding:0px 10px 10px;
}
.thankyou-wrapper p{
  font:26px Arial, Helvetica, sans-serif;
  text-align:center;
  color:#333333;
  padding:5px 10px 10px;
}
.thankyou-wrapper a{
  font:26px Arial, Helvetica, sans-serif;
  text-align:center;
  color:#ffffff;
  display:block;
  text-decoration:none;
  width:250px;
  background:#E47425;
  margin:10px auto 0px;
  padding:15px 20px 15px;
  border-bottom:5px solid #F96700;
}
.thankyou-wrapper a:hover{
  font:26px Arial, Helvetica, sans-serif;
  text-align:center;
  color:#ffffff;
  display:block;
  text-decoration:none;
  width:250px;
  background:#F96700;
  margin:10px auto 0px;
  padding:15px 20px 15px;
  border-bottom:5px solid #F96700;
}
</style>
</body>
</html>
