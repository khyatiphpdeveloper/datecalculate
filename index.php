<?php
require 'checkdate.php';
?>
<html>
<head>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.min.css"/>
<script src="js/jquery.datetimepicker.js"></script>
<script>
// Display Current date time start
    function display_c(){
        var refresh=1000; // Refresh rate in milli seconds
        mytime=setTimeout('display_ct()',refresh)
    }
    function display_ct() {
        var x = new Date()
        jQuery("#ct").text(x);
        display_c();
    }
// Display Current date time end

 jQuery( document ).ready(function() {
		jQuery("#numberofdays").empty();
		jQuery("#resultbox").hide();
		jQuery('#start_date').datetimepicker({
		lang:'en'
		});
		jQuery('#end_date').datetimepicker({
		lang:'en'
		});
	 
    jQuery('#dateform').submit(function(e) {
		e.preventDefault();
        jQuery.ajax({
            type: "POST",
		    url: 'checkdate.php',
            data: jQuery(this).serialize(),
            success: function(response)
            {
                if(jQuery("#start_timezone").val())
                {
                    var stimezone=jQuery("#start_timezone").val();
                } 
                else
                {
                    var stimezone="UTC";
                }
                if(jQuery("#end_timezone").val())
                {
                    var etimezone=jQuery("#end_timezone").val();
                }else
                {
                    var etimezone="UTC";
                }
                jQuery("#numberofdays").empty();
		      
                jQuery("#resultbox").show();
			 
				var jsonData = JSON.parse(response);
                jQuery("#dateselected").text("From: "+ jQuery("#start_date").val() +"  "+ stimezone +" Time To: "+ jQuery("#end_date").val() +"  "+ etimezone + " " );
				jQuery( "#numberofdays" ).append("<tr><td>Diffrence between two timezone: "+jsonData.days +" days,  " +jsonData.hours +" hours " +jsonData.minutes +" minutes and " +jsonData.seconds  +" seconds </p>" );
                jQuery( "#numberofdays" ).append( "<tr><td>Number of days between two datetime : <b>"+jsonData.number_of_days+"</b> day(s) </td></tr>" );
				jQuery( "#numberofdays" ).append( "<tr><td>Number of Weekdays between two datetime : <b>"+jsonData.week_days+"</b> working day(s)</td></tr>" );
                jQuery( "#numberofdays" ).append( "<tr><td>Number of Weeks between two datetime : <b>"+jsonData.weeks+"</b> week(s)</td></tr>" );
                if(jsonData.total_hours)
                {
                    jQuery( "#numberofdays" ).append( "<tr><td>Number of hours between two datetime : <b>"+jsonData.total_hours+"</b> hours(s)</td></tr>" );
                }
                if(jsonData.total_minutes)
                {
                    jQuery( "#numberofdays" ).append( "<tr><td>Number of minutes between two datetime : <b>"+jsonData.total_minutes+"</b> minute(s)</td></tr>" );
                }
                if(jsonData.total_seconds)
                {
                    jQuery( "#numberofdays" ).append( "<tr><td>Number of seconds between two datetime : <b>"+jsonData.total_seconds+"</b> second(s)</td></tr>" );
                }
            }
       });
     });
	});
</script>
<style>
.card {
    margin-left: 10px;
    margin-right: 10px
}
.bx-result {
    background-color: #f4fcf0;
    border-color: #cbdfc3;
}
</style>
</head>
<?php
	  $timezone_list = DateTimeZone::listIdentifiers(DateTimeZone::AUSTRALIA);  // Fetch autralian timezone list
?>
<body onload=display_ct();>  
<div class="container">
     <div class="mt-5 ">
         <h1 class="text-center">Date Calculator</h1>
    </div>
    <h4 id='ct' ></h4>
	<div class="row">
    <div class="col-lg-12 center">
        <div class="card mt-2 mx-auto p-2 bg-light">
            <form method="post" name="dateform" id="dateform">
                <div class="card-body bg-light">
                    <div class="container">
                            <div class="controls ">
                                <div class="row">
									<div class="col-md-2"></div>
                                    <div class="col-md-4">
                                        <div class="form-group"> 
                                            <label for="form_name"><h4><b>Start Date*</b></h4></label> 
                                            <input type="text" autocomplete="off" placeholder="Enter Start Date" name="start_date" id="start_date" class="form-control" required="required"> 
										</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group"> <label for="form_need"><h4><b>Start Date Timezone</b></h4></label> 
                                            <select name="start_timezone" id="start_timezone" class="form-control">
                                                <option value="" selected disabled>Select timezone</option>
                                                <?php
                                                    foreach($timezone_list as $list) { ?>
                                                    <option value="<?= $list ?>"><?= $list ?></option>
                                                <?php
                                                    } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
									<div class="col-md-2"></div>
                                    <div class="col-md-4">
                                        <div class="form-group"> 
                                            <label for="form_name"><h4><b>End Date *</b></h4></label> 
                                            <input id="end_date" type="text" autocomplete="off" placeholder="Enter end Date" name="end_date" class="form-control"  required="required" data-error="end date is required.">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group"> <label for="form_need"><h4><b>End Date Timezone</b></h4></label> 
                                            <select name="end_timezone" id="end_timezone" class="form-control">
                                                <option value="" selected disabled>Select timezone</option>
                                                <?php
                                                foreach($timezone_list as $list) { ?>
                                                <option value="<?= $list ?>"><?= $list ?></option>
                                                <?php
                                                    } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 p-2">
									<div class="col text-center">
										<button type="submit" class="btn btn-primary center">Calculate</button>
									</div>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>	
                 </form>
	        </div>
        </div>
    </div>
    <div class="row resultbox" id="resultbox">
        <div class="col-lg-12">
            <div class="card mt-2 mx-auto p-4 bg-light">
                <div class="card-body bg-light">
                    <div class="container">
                        <h3><center>Result</center></h3>
                        <div class="controls row ">
                            <table class="table">
								<thead class="thead-dark">
									<tr><th colspan="3">  <span id="dateselected" ></span>
									<small>(Not including the end date.)</small></th></tr>
								</thead>
								<tbody id="numberofdays" ></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div> 
</body>
</html>