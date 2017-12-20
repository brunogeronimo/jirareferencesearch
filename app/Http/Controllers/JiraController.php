<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Jira\JiraService;

class JiraController extends BaseController
{
	public function search(Request $request){
		$content = json_decode($request->getContent());
		$fields = ['username', 'password', 'jql'];

		foreach ($fields as $field) {
			if (!isset($content->{$field})){
				return response()->json([
					'status' => 'error',
					'message' => "{$field} must be set"
				], 400);
			}
			if ($content->{$field} == ''){
				return response()->json([
					'status' => 'error',
					'message' => "{$field} cannot be blank"
				], 400);
			}
		}

		$username = trim($content->username);
		$password = trim($content->password);
		$jql = trim($content->jql);

		$jiraService = new JiraService($username, $password);

		return response()->json($jiraService->search($jql), 200);
	}
}