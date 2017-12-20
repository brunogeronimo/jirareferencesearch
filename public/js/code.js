$(document).ready(function(){

	$('.loginForm').submit(function(e){
		e.preventDefault();
		$('.loginForm').addClass('hide');
		$('.searchForm').removeClass('hide');
	});

	$('.searchForm').submit(function(e){
		e.preventDefault();
		let username = $('#username').val();
		let password = $('#password').val();
		let jql = $('#jql').val();
		var jiraInterface = new JiraInterface(username, password);
		jiraInterface.search(jql, 
			function(data){
				makeTable(data);
			},
			function(data, textStatus){
				alert('An error has ocurred while handling your request. Please, refresh the page and try again');
			}
		);
	});


	function makeTable(data){
		var table = '<table width="100%">';
		table += '<tr>';
		table += 	'<td>' + 'JIRA' + '</td>';
		table += 	'<td>' + 'Status' + '</td>';
		table += 	'<td>' + 'References' + '</td>';
		table += '</tr>';

		console.log(data);
		$(data).each(function(index, jira){
			table += '<tr>';
			table += 	'<td>' + jira.key + '</td>';
			table += 	'<td>' + jira.status + '</td>';
			table += 	'<td>';


			$(jira.referencesStatus).each(function(index, reference){
				if (reference.error === undefined){
					table += reference.key + '-' + reference.status;
				}else{
					table += reference.key + '---' + 'Error: ' + reference.error.message;
				}
				table += '<br/>';
			});


			table +=	'</td>';
			table += '</tr>';
		})


		table += '</table>';
		$('.result').html(table);

	}

});