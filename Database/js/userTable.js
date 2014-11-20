$(document).ready(function(){
	for(var key in data){
		console.log(data[key]['stats']);
		var player_stats = JSON.stringify(data[key]['stats']);
		player_stats = player_stats.substring(1, player_stats.length-1);
		player_stats = player_stats.replace(/['"]+/g, '');
		player_stats = player_stats.replace(',', ' ');
		player_stats = player_stats.replace(',', ' ');
		player_stats = player_stats.replace('POINTS', 'Pts');
		player_stats = player_stats.replace('REBOUNDS', 'Rbs');
		player_stats = player_stats.replace('ASSISTS', 'Asts');
		player_stats = player_stats.replace('YDS', 'Yds');
		player_stats = player_stats.replace('TD', 'Tds');
		player_stats = player_stats.replace('goals', 'Goals');
		player_stats = player_stats.replace('assists', 'Asts');
		var row = "<tr><td>" + data[key]['sport'] + "</td><td>" + data[key]['athlete_name'] + "</td><td>" + data[key]['athlete_team'] + "</td><td>" + data[key]['position'] + "</td><td>" + data[key]['athlete_points'] + "</td><td>" + player_stats+ "</td><td>" + "</td></tr>";
		$("#mainTable").append(row);
	}
});


