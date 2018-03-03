<?php
namespace App\Jira;
use GuzzleHttp\Exception\ClientException;
use App\Jira\JiraInterface;

class JiraService
{
	private $username;
	private $password;

	function __construct(string $username = '', string $password = ''){
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

	public function setUsername(string $username){
		$this->username = $username;
	}

	public function getPassword(){
		return $this->password;
	}

	public function setPassword(string $password){
		$this->password = $password;
	}

	public function searchByJiraIdForDebug(string $jiraId = ''){
		if ($jiraId === ''){
			throw new Exception("A JIRA ID must be informed", 1);
		}

		$jiraInterface = JiraInterface::create()
							->setUsername($this->getUsername())
							->setPassword($this->getPassword());

		$issueResponse = json_decode($jiraInterface->retrieveUrl("/issue/{$jiraId}"));
		return $issueResponse;
	}

	public function search(string $jql = ''){
		$dateMask = env('MASK_OUTPUT_DATE', 'Y-m-d H:i:s');
		if ($jql === ''){
			throw new Exception("Jql must be informed", 1);
		}

		$jiraInterface = JiraInterface::create()
								->setUsername($this->getUsername())
								->setPassword($this->getPassword());

		$issues = json_decode($jiraInterface->search($jql))->issues;
		
		$warrantyUrls = [];
		$issuesInfo = [];
		$referenceInfo = [];

		$response = [];
		foreach ($issues as $issue) {
			$reference = (isset($issue->fields->customfield_10510) ? $issue->fields->customfield_10510 : null);
			$createdAt = new \DateTime($issue->fields->created);
			$updatedAt = new \DateTime($issue->fields->updated);
			$aux = [
				'key' => $issue->key,
				'status' => $issue->fields->status->name,
				'description' => $issue->fields->description,
				'assignee' => $issue->fields->assignee->key,
				'reporter' => $issue->fields->creator->key,
				'references' => $reference,
				'createdAt' => $createdAt->format($dateMask),
				'updatedAt' => $updatedAt->format($dateMask),
				'referencesStatus' => []
			];

			if (!isset($issuesInfo[$issue->fields->status->id])){
				$issuesInfo[$issue->fields->status->id]['id'] = $issue->fields->status->id;
				$issuesInfo[$issue->fields->status->id]['total'] = 0;
				$issuesInfo[$issue->fields->status->id]['name'] = $issue->fields->status->name;
			}
			$issuesInfo[$issue->fields->status->id]['total']++;

			$status = array();
			if ($reference !== null){
				$refArray = explode(' ', $reference);
				foreach ($refArray as $ref) {
					try{
							$referenceInfo = ['key' => $ref];
							$jiraSearchResult = json_decode($jiraInterface->retrieveUrl('/issue/' . $ref));
							$referenceInfo['status'] = $jiraSearchResult->fields->status->name;
							$referenceInfo['assignee'] = $jiraSearchResult->fields->assignee->key;
							if (isset($jiraSearchResult->fields->fixVersions[0])){
								$referenceInfo['fixedVersion'] = $jiraSearchResult->fields->fixVersions[0]->name;
							}
							$referenceInfo[''] = $jiraSearchResult->fields->assignee->key;
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

			$response['jiras'][] = $aux;
		}
		$issuesInfo = array_values($issuesInfo);
		$response['total'] = $issuesInfo;

		return $response;
	}


}