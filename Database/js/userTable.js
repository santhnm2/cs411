$(document).ready(function(){
	for(var key in data){
		var row = "<tr><td>" + data[key]['sport'] + "</td><td>" + data[key]['athlete_name'] + "</td><td>" + data[key]['athlete_team'] + "</td><td>" + data[key]['position'] + "</td><td>" + data[key]['athlete_points'] + "</td><td>" + "</td></tr>";
		$("#mainTable").append(row);
	}
});


