<?php
	namespace App\Jira;
	use GuzzleHttp\Exception\GuzzleException;
	use GuzzleHttp\Client;

	class JiraInterface
	{
		private $username;
		private $password;
		private $url;
		private $client;
		private $debug = false;

		private const JIRA_DEFAULT_API_URL = '/rest/api/2';

		public function __construct(array $config = []){
			$this->url = env('JIRA_URL', '');
			if ($this->url === ''){
				throw new Exception("JIRA_URL value must be set on .env file");
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

		private function instantiateClient($force = false){
			if ($force || $this->client === null){
				$this->client = new \GuzzleHttp\Client();
			}
		}

		private function makeUrl($endpoint = ''){
			return $this->getUrl() . self::JIRA_DEFAULT_API_URL . $endpoint;
		}

		public function setUsername($username){
			$this->username = $username;
			return $this;
		}

		public function setPassword($password){
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

		public function setDebug(bool $debug = false){
			$this->debug = $debug;
			return $this;
		}

		private function getClient(bool $force = false){
			$this->instantiateClient($force);
			return $this->client;
		}

		private function getAuthentication(){
			return [$this->username, $this->password];
		}

		public function search($jql = ''){
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

		public function retrieveUrl($url = '', $fillWithUrl = true){
			if ($url === ''){
				throw new Exception("URL must be set");
			}
			if ($fillWithUrl){
				$url = $this->makeUrl($url);
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

	}
?>