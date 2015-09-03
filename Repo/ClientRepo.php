<?php 
class ClientRepo{

	public function getClients($request)
	{
		// Initial response is bad request
		$response = 400;

		$clients = $GLOBALS['con']->from('clients');
		$data = array();

		foreach($clients as $client)
		{
		    $clientData = $this->getClient(array('id' => $client['id']));
			$data[] = $clientData['data'];
		}

		$response = 200;
			
		return array('code' => $response,'data' => $data);
			
	}

	public function getClient($request)
	{
		// Initial response is bad request
		$response = 400;
		$projectRepo = new ProjectRepo();

		$clients = $GLOBALS['con']->from('clients')->where('id',$request['id'])->fetch();
		$data = array();

	   	$client['web_url'] 	= Image::getRootPath(false).'data/client/'.$clients['path'];
	    $projectData = $projectRepo->getProject(array('id' => $clients['project_id']));
	    if(!empty($projectData['data']))
	   	{
			//$clients['project_name'] = $projectData['data']['name'];
	    }
	    else
	    {
	    	$clients['project_name'] = "";
	    }
		$data = $clients;

		$response = 200;
			
		return array('code' => $response,'data' => $data);
			
	}

	public function deleteClient($request)
	{
		$response = 400;

		if(!empty($id))
		{
			$count = $GLOBALS['con']->from('clients')->where('id',$request['id'])->count();
			if($count > 0)
			{
				$query 		= $GLOBALS['con']->deleteFrom('clients')->where('id', $request['id'])->execute();

				$response 	= 200;
			}
			else
			{
				$response = 400;
			}
		}
		else
		{
			$response = 400;
		}
		return $response;

	}

	public function addClient($request)
	{
		$response = 400;
		if(!empty($request))
		{
	
			$values = array('project_id' => $request['project_id'],'name' => $request['name'],'path' => $request['path'], 'description' => $request['description']);
			$query = $GLOBALS['con']->insertInto('clients', $values)->execute();

			$response = '200';
			
		}
		else
		{
			$response = '400';
		}

		return $response;

	}

	public function editClient($request)
	{
		$response = 400;

		if(!empty($request['id']))
		{
			$count = $GLOBALS['con']->from('clients')->where('id',$request['id'])->count();

			if($count > 0)
			{
				$values = array('project_id' => $request['project_id'],'name' => $request['name'],'path' => $request['path'], 'description' => $request['description']);
				$query = $GLOBALS['con']->update('clients', $values, $request['id'])->execute();

				$response = 200;
			}
			else
			{
				$response = 400;
			}
		}
		return $response;
	}



}