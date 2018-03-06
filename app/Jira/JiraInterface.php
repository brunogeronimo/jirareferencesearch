<?php
	namespace App\Jira;
	use \GuzzleHttp\Exception\GuzzleException;
	use \GuzzleHttp\Client;

	class JiraInterface
	{
		private $username;
		private $password;
		private $url;
		private $client;
		private $debug = false;

		const JIRA_DEFAULT_API_URL = '/rest/api/2';

		public function __construct($config = []){
			if (!is_array($config)){
				throw new \Exception("Config variable must be an array");
			}
			$this->url = env('JIRA_URL', '');
			if ($this->url === ''){
				throw new \Exception("JIRA_URL value must be set on .env file");
			}
			if (isset($config['username'])){
				$this->username = $username;
			}
			if (isset($config['password'])){
				$this->password = $password;
			}
			if (isset($config['debug'])){
				$this->debug = $debug;
			}
			return $this;
		}

		public static function create(){
			$instance = new self();
			return $instance;
		}

		private function instantiateClient(){
			if ($this->client === null){
				$this->client = new \GuzzleHttp\Client();
			}
		}

		private function makeUrl($endpoint = ''){
			if (!is_string($endpoint)){
				throw new Exception("Endpoint must be a string");
			}
			return $this->getUrl() . self::JIRA_DEFAULT_API_URL . $endpoint;
		}

		public function setUsername($username){
			if (!is_string($username)){
				throw new Exception("Username must be a String");
			}
			$this->username = $username;
			return $this;
		}

		public function setPassword($password){
			if (!is_string($password)){
				throw new Exception("Password must be a String");
			}
			$this->password = $password;
			return $this;
		}

		public function getUrl(){
			return $this->url;
		}

		public function getDebug(){
			return $this->debug;
		}

		public function setUrl($url){
			$this->url = $url;
			return $this;
		}

		public function setDebug($debug = false){
			if (!is_bool($debug)){
				throw new Exception("Debug must be a boolean");
			}
			$this->debug = $debug;
			return $this;
		}

		private function getClient(){
			$this->instantiateClient();
			return $this->client;
		}

		private function getAuthentication(){
			return [$this->username, $this->password];
		}

		public function search($jql = ''){
			if (!is_string($jql)){
				throw new Exception("JQL must be a String");
			}
			if ($jql === ''){
				throw new Exception("JQL must be set");
			}
			$response = $this->getClient()->get(
				$this->makeUrl('/search?expand=status'),
				[
					'query' => [
						'jql' => $jql,
						'startAt' => 0,
						'maxResults' => 3500
					],
					'auth' => $this->getAuthentication(),
					'debug' => $this->getDebug()
				]
			);
			return $response->getBody();
		}

		public function retrieveCustomUrl($url = ''){
			if ($url === ''){
				throw new Exception("URL must be set");
			}

			$response = $this->getClient()->get(
				$url,
				[
					'auth' => $this->getAuthentication(),
					'debug' => $this->getDebug()
				]
			);
			return $response->getBody();
		}

		public function retrieveUrl($url = ''){
			if (!is_string($url)){
				throw new Exception("URL must be a string");
			}
			if ($url === ''){
				throw new Exception("URL must be set");
			}

			$url = $this->makeUrl($url);
			$response = $this->getClient()->get(
				$url,
				[
					'auth' => $this->getAuthentication(),
					'debug' => $this->getDebug()
				]
			);
			return $response->getBody();
		}

	}
?>