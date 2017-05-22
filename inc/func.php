<?php
function q($query){
	global $SQLiConnection;
	$result = mysqli_query($SQLiConnection, $query);
	if(!$result){
		die(mysqli_error($SQLiConnection));
	}
	else{
		return $result;
	}
}

function f($query){
	return mysqli_fetch_assoc($query);
}

function n($query){
	return mysqli_num_rows($query);
}

function clean($string){
	global $SQLiConnection;
	$string = trim($string);
	$string = mysqli_real_escape_string($SQLiConnection, $string);
	return $string;
}

function getal($getal){
	return number_format($getal, 0, ",", ".");
}

function bedrag($bedrag){
	return number_format($bedrag, 2, ",", ".");
}

function successmsg($text){
	return "<div class='alert alert-success' role='alert'>".$text."</div>";
}

function infomsg($text){
	return "<div class='alert alert-info' role='alert'>".$text."</div>";
}

function warningmsg($text){
	return "<div class='alert alert-warning' role='alert'>".$text."</div>";
}

function errormsg($text){
	return "<div class='alert alert-danger' role='alert'>".$text."</div>";
}

function idToName($id){
	$uQuery = q("SELECT name FROM user_users WHERE id = '".clean($id)."'");
	if(n($uQuery) != 1){
		return "Onbekend";
	}
	else{
		$uFetch = f($uQuery);
		return $uFetch['name'];
	}
}


// Formats a timestamp, returns hh:ii when it was today, the day of the week if it was less than 7 days ago or otherwise the date.
function formatPastDate($date){
	global $WeekDagen, $Maanden;
	
	if(date("Ymd") == date("Ymd", $date)){
		return date("H:i", $date);
	}
	else{
		// Get the start of today
		$till = strtotime(date("d-m-Y 00:00:00"));
		// Get the start of 6 days ago
		$from = strtotime(date("d-m-Y 00:00:00", strtotime("-6 days")));
		if($date > $from && $date < $till){
			return $WeekDagen[date("w", $date)];
		}
		else{
			return date("d ", $date). $Maanden[date("n", $date)]. (date("Y", $date) != date("Y") ? date(" Y", $date) : "") ;
		}
	}
}
?>