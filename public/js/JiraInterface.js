class JiraInterface{

	constructor(username, password){
		if (username === ''){
			throw 'ERR_BLANK_USERNAME';
		}
		if (password === ''){
			throw 'ERR_BLANK_PASSWORD';
		}
		this._username = username;
		this._password = password;
		Object.freeze(this);
	}


	search(jql, successCallback, errorCallback){
		if (jql === ''){
			throw 'ERR_BLANK_JQL';
		}
		let data = {};
		data.username = this._username;
		data.password = this._password;
		data.jql = jql;
		$.ajax({
				method: 'POST',
				url: "/search",
				contentType: 'application/json',
				//dataType: 'json',
				data: JSON.stringify(data),
				success:function(data){
					if (successCallback !== null){
						successCallback(data);
					}
				},
				error: function(data, textStatus){
					if (errorCallback !== null){
						errorCallback(data, textStatus);
					}
				}
		});
	}
}