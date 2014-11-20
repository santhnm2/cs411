$(document).ready(function(){
	var count = 1;
	for(var key in data){
		var row;
		if(name == data[key]['username'])
			row = '<tr bgcolor="#FFFF00"><td>' + count + "</td><td>" + data[key]['username'] + "</td><td>" + data[key]['Points'] + "</td></tr>";
		else{
			row = '<tr><td>' + count + "</td><td><a data-toggle='modal' data-id='" + data[key]['username'] + "' class='open-AddBookDialog' href='#addBookDialog'>" + data[key]['username'] + "</a></td><td>" + data[key]['Points'] + "</td></tr>";
			console.log(row);
		}
		$("#mainTable").append(row);
		count++;
	}
});

