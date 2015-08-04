<?php 
class ClientRepo{

	public function getClients($request)
	{
			$requestData = $request;
			// Initial response is bad request
			//$response = 400;

			
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
			//$response = 400;
				$projectRepo = new ProjectRepo();

				$exists = $GLOBALS['con']->from('clients')->where('id',$request['id']);
				$data = array();

				foreach($exists as $client)
		    	{	
		    		 $client['web_url'] 	= Image::getRootPath(false).'data/client/'.$client['path'];
		    		$projectData = $projectRepo->getProject(array('id' => $client['project_id']));

		    		if(!empty($projectData['data']))
		    		{
				    		$client['project_name'] = $projectData['data']['name'];
		    		}
		    		else
		    		{
		    			$client['project_name'] = "";
		    		}
					$data = $client;

				}

				$response = 200;
			
			return array('code' => $response,'data' => $data);
			
	}

	public function deleteClient($request)
	{
		$id = $request['id'];
		$response = 400;

		if(!empty($id))
		{
			$count = $GLOBALS['con']->from('clients')->where('id',$id)->count();
			if($count > 0)
			{
				$query 		= $GLOBALS['con']->deleteFrom('clients')->where('id', $id)->execute();

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
			$projectId = $GLOBALS['con']->insertInto('clients', $values)->execute();

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