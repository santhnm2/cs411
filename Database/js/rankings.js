$(document).ready(function(){
	var count = 1;
	for(var key in data){
		var row;
		if(name == data[key]['username'])
		row = '<tr bgcolor="#FFFF00"><td>' + count + "</td><td>" + data[key]['username'] + "</td><td>" + data[key]['Points'] + "</td></tr>";
		else{
			row = '<tr><td>' + count + "</td><td><a href='show_players.php?players_for=" + data[key]['username'] + "'>" + data[key]['username'] + "</a></td><td>" + data[key]['Points'] + "</td></tr>";
		}
		$("#mainTable").append(row);
			count++;
	}
});