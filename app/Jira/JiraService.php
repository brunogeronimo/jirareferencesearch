<?php
namespace App\Jira;
use GuzzleHttp\Exception\ClientException;
use App\Jira\JiraInterface;

class JiraService
{
	private $username;
	private $password;

	function __construct($username = '', $password = ''){
		if ($username == ''){
			throw new Exception("Username must be set");
		}
		if ($password == ''){
			throw new Exception("Password must be set");
		}

		$this->username = $username;
		$this->password = $password;
	}

	public function getUsername(){
		return $this->username;
	}

	public function setUsername($username){
		$this->username = $username;
	}

	public function getPassword(){
		return $this->password;
	}

	public function setPassword($password){
		$this->password = $password;
	}

	public function search($jql = ''){
		if ($jql === ''){
			throw new Exception("Jql must be informed", 1);
		}

		$jiraInterface = JiraInterface::create()
								->setUsername($this->getUsername())
								->setPassword($this->getPassword());

		$issues = json_decode($jiraInterface->search($jql))->issues;
		
		$warrantyUrls = [];
		$issuesInfo = [];

		$response = [];
		foreach ($issues as $issue) {
			$reference = (isset($issue->fields->customfield_10510) ? $issue->fields->customfield_10510 : null);
			$aux = [
				'key' => $issue->key,
				'status' => $issue->fields->status->name,
				'references' => $reference,
				'referencesStatus' => []
			];

			$referenceInfo = [];
			if ($reference !== null){
				$refArray = explode(' ', $reference);
				foreach ($refArray as $ref) {
					try{
							$referenceInfo = ['key' => $ref];
							$blabla = json_decode($jiraInterface->retrieveUrl('/issue/' . $ref));
							$referenceInfo['status'] = $blabla->fields->status->name;
					}catch(\GuzzleHttp\Exception\RequestException $e){
						if ($e->hasResponse()){
							$referenceInfo['error'] = [
								'code' => $e->getCode(),
								'message' => $e->getMessage()
							];
						}
					}
					$aux['referencesStatus'][] = $referenceInfo;
				}
			}

			$response[] = $aux;
		}

		return $response;
	}


}