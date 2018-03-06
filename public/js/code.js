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
		$(".result").html('');
		jiraInterface.search(jql, 
			function(data){
				makeTable(data.jiras);
				console.log(data);
				makeTotalizerTable(data.total);
			},
			function(data, textStatus){
				alert('An error has ocurred while handling your request. Please, refresh the page and try again');
			}
		);
	});

	function makeTotalizerTable(data){
		let table = document.createElement('table');
		table.style.width = '50%';
		let tBody = document.createElement('tbody');
		let total = 0;
		let tr = createTr();
		tr.appendChild(createTh('Status'));
		tr.appendChild(createTh('Total'));
		tBody.appendChild(tr);
		$(data).each(function(index, status){
			console.log(status);
			tr = createTr();
			tr.appendChild(createTd(status.name));
			tr.appendChild(createTd(status.total));
			total += status.total;
			tBody.appendChild(tr);
		});
		tr = createTr();
		tr.appendChild(createTd('Total'));
		tr.appendChild(createTd(total));
		tBody.appendChild(tr);
		table.appendChild(tBody);
		let title = document.createElement('h1');
		title.innerText = 'Totalizers';
		$('.result').append(title);
		$('.result').append(table);
	}

	function makeTable(data){
		let table = document.createElement('table');
		table.style.width = '100%';
		let tBody = document.createElement('tbody');

		let tr = createTr();

		tr.appendChild(createTh('JIRA'));
		tr.appendChild(createTh('Status'));
		tr.appendChild(createTh('Assignee'));
		tr.appendChild(createTh('Reporter'));
		tr.appendChild(createTh('Created at'));
		tr.appendChild(createTh('Updated at'));
		
		tr.appendChild(createTh('Description'));

		tr.appendChild(createTh('References'));
		tBody.appendChild(tr);

		$(data).each(function(index, jira){
			tr = createTr();
			tr.appendChild(createTd(jira.key));
			tr.appendChild(createTd(jira.status));
			tr.appendChild(createTd(jira.assignee));
			tr.appendChild(createTd(jira.reporter));
			tr.appendChild(createTd(jira.createdAt));
			tr.appendChild(createTd(jira.updatedAt));

			tr.appendChild(createTd(jira.description));

			let td = createTd();
			let texts = '';
			$(jira.referencesStatus).each(function(index, reference){
				if (reference.error === undefined){
					texts += reference.key + '-' + reference.status + ':' + reference.assignee;
					if (reference.fixedVersion !== undefined){
						texts += ' [' + reference.fixedVersion + ']';
					}
				}else{
					texts += reference.key + '---' + 'Error: ' + reference.error.message;
				}
			});
			
			tr.appendChild(createTd(texts));
			tBody.appendChild(tr);
		});
		table.appendChild(tBody);
		$('.result').append(table);
	}

	function createElement(element){
		return document.createElement(element);
	}

	function createElementWithText(element, text){
		let component = createElement(element);
		component.appendChild(document.createTextNode(text));
		return component;
	}

	function createTextNode(text){
		return document.createTextNode(text);
	}

	function createTd(text){
		return createElementWithText('td', text);
	}

	function createTh(text){
		return createElementWithText('th', text);
	}

	function createTr(){
		return createElement('tr');
	}

});