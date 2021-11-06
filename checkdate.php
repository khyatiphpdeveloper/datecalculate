<?php
class CDate 
{
	function __construct() { 
    }

public function calculate_date($start_date,$end_date,$k=0)
{
	$datearr=array();
	$startdate= date('Y-m-d H:i:s',strtotime($start_date));
	$enddate= date('Y-m-d H:i:s',strtotime($end_date));

	//check timezone selected or else it will take default timezone
	if(isset($_POST['start_timezone']))
	{
		$startdate = new DateTime($startdate,new DateTimeZone($_POST['start_timezone'])); //Create a DateTime object for the start date.
	}else{
		$startdate = new DateTime($startdate); //Create a DateTime object for the start date.
	}

	if(isset($_POST['end_timezone']))
	{
		$enddate = new DateTime($enddate, new DateTimeZone($_POST['end_timezone'])); //Create a DateTime object for the end date.
	}else
	{
		$enddate = new DateTime($enddate); //Create a DateTime object for the end date.
	}
	//Calcualte diffrent timezone 
	$interval = $startdate->diff($enddate);     //Get the difference between the two dates in days.
	$hours   = $interval->format('%h');      
	$minutes = $interval->format('%i');
	$days = $interval->format('%d');
	$seconds = $interval->format('%s');

	$datearr['days']=$days;
	$datearr['hours']=$hours;
	$datearr['minutes']=$minutes;
	$datearr['seconds']=$seconds;

	$startdate= strtotime($startdate->format('Y-m-d H:i:s')); //convert date object to time i.e 1635721200
	$enddate= strtotime($enddate->format('Y-m-d H:i:s')); //convert date object to time i.e 1637142600

	//calculate working days between two dates start
	$workingDays = 0;

	for($i=$startdate; $i<$enddate; $i = $i+(60*60*24) ) //24 hours = 86400 seconds
	{
		if(date("N",$i) <= 5) $workingDays = $workingDays + 1;
	}
	$datearr['working_days']=$workingDays;
	//calculate working days between two dates end

	//Number of days between two dates 
	$start_date = strtotime($start_date);
	$end_date = strtotime($end_date);
	$datediff = ($end_date)- ($start_date) ;
	$number_of_days = round($datediff / (60 * 60 * 24));
 	$datearr['number_of_days']=$number_of_days;
 
	//calculate weeks Divide the days by 7
	$datearr['weeks']=floor($days/7);  //Round down with floor and return the difference in weeks.

	if($k>0) //check third parameter value passed then it calculate
	{
		if($k==1) //call calculate_time function to get hours
		{
			$datearr['total_hours']= CDate::calculate_time($days,$hours,$minutes,$seconds,$k); 
		}
		else if($k==2) //call calculate_time function to get minutes
		{
			$datearr['total_minutes']= CDate::calculate_time($days,$hours,$minutes,$seconds,$k); 
		}
		else if($k==3) //call calculate_time function to get seconds
		{
			$datearr['total_seconds']= CDate::calculate_time($days,$hours,$minutes,$seconds,$k); 
		}
	}
	return json_encode($datearr);
}

public static function calculate_time($number_of_days,$hour,$minute,$second,$i) //calculate hours minute seconds between two dates 
{
	switch ($i) {
	  case 1: //convert into hours
		$hours=floor(($number_of_days)*24);
		return ($hours+$hour);
		break;
	  case 2: //convert into minutes
	 	$minutes=floor(($number_of_days)*24*60);
		return ($minutes+$minute+($hour*60));
		break;
	  case 3: //convert into seconds
		$sm=(($hour * 3600) + ($minute * 60) + $second);
	   $seconds=floor(($number_of_days)*24*60*60);
		return ($seconds+$sm);
    	break;
	  default:
		return 0;
	}
}
}
if(isset($_POST['start_date']) && isset($_POST['end_date'])) 
{
	$CDate = new CDate();
	echo $CDate->calculate_date($_POST['start_date'],$_POST['end_date'],3); //function third parameter is optional 1:  hours , 2: minutes, 3: seconds 
}

?>