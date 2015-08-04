<?php 
class ProjectVideoRepo{

	public function addProjectVideo($request)
	{
		$response = 400;
		if(!empty($request))
		{
			$count = count($request['videos']);
			for($t=0;$t<$count;$t++)
			{
				echo $request['videos'][$t];
			}
			
			foreach($request['videos'] as $video)
			{
				$values = array('project_id' => $request['project_id'],'embed_code' => '101','source' => $video);
				$query = $GLOBALS['con']->insertInto('project_videos', $values)->execute();
			}


			$response = '200';
		}
		else
		{
			$response = '400';
		}


		return $response;

	}


	// public function getProjects($request)
	// {
	// 		$requestData = $request;
	// 		// Initial response is bad request
	// 		//$response = 400;

			
	// 			$exists = $GLOBALS['con']->from('projects');
	// 			$data = array();

	// 			foreach($exists as $projects)
	// 	    	{
	// 	    		$projectData = $this->getProject(array('id' => $projects['id']));
	// 				$data[] = $projectData['data'];
	// 			}

	// 			$response = 200;
			
	// 		return array('code' => $response,'data' => $data);
			
	// }

	// public function getProject($request)
	// {
	// 		// Initial response is bad request
	// 		//$response = 400;
	// 			$catRepo = new ProjectCategoryRepo();
	// 			$exists = $GLOBALS['con']->from('projects')->where('id',$request['id']);
	// 			$data = array();

	// 			foreach($exists as $projects)
	// 	    	{
	// 	    		$catData = $catRepo->getProjectCategory(array('id' => $projects['cat_id']));
	// 	    		if(!empty($catData['data']))
	// 	    		{
	// 	    			if(isset($catData['data']))
	// 	    			{
	// 			    		$projects['cat_name'] = $catData['data']['name'];
	// 	    			}
	// 	    		}
	// 				$data = $projects;

	// 			}

	// 			$response = 200;
			
	// 		return array('code' => $response,'data' => $data);
			
	// }

	// public function deleteProject($request)
	// {
	// 	$id = $request['id'];
	// 	$response = 400;

	// 	if(!empty($id))
	// 	{
	// 		$exists = $GLOBALS['con']->from('projects')->where('id',$id)->count();
	// 		if($exists)
	// 		{
	// 			$query = $GLOBALS['con']->deleteFrom('projects')->where('id', $id)->execute();
	// 			$response = 200;
	// 		}
	// 		else
	// 		{
	// 			$response = 400;
	// 		}
	// 	}
	// 	else
	// 	{
	// 		$response = 400;
	// 	}
	// 	return $response;

	// }

	
	public function editProject($request)
	{
		$response = 400;

		if(!empty($request['id']))
		{
			$count = $GLOBALS['con']->from('projects')->where('id',$request['id'])->count();

			if($count > 0)
			{
				$values = array('cat_id' => $request['cat_id'],'name' => $request['name'],'location' => $request['location'],'value' => $request['value'],'client' => $request['client'],'heading' => $request['heading'],'description' => $request['description'],'date_created' => date("Y-m-d H:i:s"));
				$query = $GLOBALS['con']->update('projects', $values, $request['id'])->execute();

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