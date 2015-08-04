<?php 
class TeamRepo{

	public function getTeams($request)
		{
			$requestData = $request;
			// Initial response is bad request
			//$response = 400;

			// If there is some data in json form
			
				$team = $GLOBALS['con']->from('team')->orderBy('sort_order','asc');
				$data = array();

				foreach($team as $teams)
		    	{
		    		$teamData 		= $this->getTeam(array('id' => $teams['id']));
					$data[] 		= $teamData['data'];

				}

				$response = 200;
					
			
			return array('code' => $response,'data' => $data);
			
	}

	public function getTeam($request)
	{
				$team = $GLOBALS['con']->from('team')->where('id',$request['id']);

				foreach($team as $teams)
		    	{
					$teams['web_url'] 	= Image::getRootPath(false).'data/team/'.$teams['path'];
					$data 				= $teams;

				}

				$response = 200;
			
			return array('code' => $response,'data' => $data);
			
	}

	public function deleteTeam($request)
	{
		$id 		= $request['id'];
		$response 	= 400;

		if(!empty($id))
		{
			$exists 		= $GLOBALS['con']->from('team')->where('id',$id)->count();
			if($exists)
			{
				$query 		= $GLOBALS['con']->deleteFrom('team')->where('id', $id)->execute();
				$response 	= 200;
			}
			else
			{
				$response 	= 400;
			}
		}
		else
		{
			$response = 400;
		}
		return $response;

	}

	public function addTeam($request)
	{

		if(!empty($request))
		{
			$name 				= $request['name'];
			$designation 		= $request['designation'];
			$bio				= $request['bio'];
			$path    			= $request['path'];
			$sort_order    		= $request['sort_order'];
			$facebook    		= $request['facebook'];
			$twitter    		= $request['twitter'];
			$google    			= $request['google'];
			$skype    			= $request['skype'];


			$values 	= array('name' => $name,'designation' => $designation,'bio' => $bio ,	'path' => $path, 'sort_order' => $sort_order, 'facebook' => $facebook, 'twitter' => $twitter, 'google' => $google, 'skype' => $skype, 'date_created' => date("Y-m-d H:i:s"));
			$query 		= $GLOBALS['con']->insertInto('team', $values)->execute();

			$response 	= '200';

		}
		else
		{
			$response = '400';
		}


		return $response;

	}

	public function editTeam($request)
	{

		if(!empty($request))
		{
			$id 				= $request['id'];
			$name 				= $request['name'];
			$designation 		= $request['designation'];
			$bio				= $request['bio'];
			$path    			= $request['path'];
			$sort_order    		= $request['sort_order'];
			$facebook    		= $request['facebook'];
			$twitter    		= $request['twitter'];
			$google    			= $request['google'];
			$skype    			= $request['skype'];


			$values 	= array('id' => $id , 'name' => $name,'designation' => $designation,'bio' => $bio ,	'path' => $path, 'sort_order' => $sort_order, 'facebook' => $facebook, 'twitter' => $twitter, 'google' => $google, 'skype' => $skype, 'date_created' => date("Y-m-d H:i:s"));
			$query 		= $GLOBALS['con']->update('team', $values,$request['id'])->execute();

			$response 	= '200';

		}
		else
		{
			$response 	= '400';
		}


		return $response;

	}

}
