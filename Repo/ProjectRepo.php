<?php 
class ProjectRepo{

	public function getProjects($request)
	{
		$exists = $GLOBALS['con']->from('projects');
		$data = array();

		foreach($exists as $projects)
		{
		    $projectData = $this->getProject(array('id' => $projects['id']));
			$data[] = $projectData['data'];
		}

		$response = 200;
			
		return array('code' => $response,'data' => $data);
			
	}

	public function getProject($request)
	{
		
		// Initial response is bad request
		//$response = 400;
		$catRepo = new ProjectCategoryRepo();
		$exists = $GLOBALS['con']->from('projects')->where('id',$request['id']);
		$data = array();

		foreach($exists as $projects)
		{
		    // $videos = $this->getVideos($request['id']);
		    // $projects['videos'] = $videos['data']; 

		    $images = $this->getImages($request['id']);
		    $projects['images'] = $images['data']; 

		    if(!empty($images['data']))
		    {
		    	if(!empty($images['data'][0]))
		    	{
		    		$projects['web_url'] = $images['data'][0]['web_url'];
		    	}
		    }


		    $catData = $catRepo->getProjectCategory(array('id' => $projects['cat_id']));
		    if(!empty($catData['data']))
		    {
		    	if(isset($catData['data']))
		    	{
				    $projects['cat_name'] = $catData['data']['name'];
		    	}
		    }
			$data = $projects;

		}

		$response = 200;
			
		return array('code' => $response,'data' => $data);
			
	}

	public function deleteProject($request)
	{
		$id = $request['id'];
		$response = 400;

		if(!empty($id))
		{
			$exists = $GLOBALS['con']->from('projects')->where('id',$id)->count();
			if($exists)
			{
				$query 		= $GLOBALS['con']->deleteFrom('projects')->where('id', $id)->execute();
				$video 		= $GLOBALS['con']->deleteFrom('project_videos')->where('project_id',$id)->execute();
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

	public function addProject($request)
	{
		$response = 400;
		if(!empty($request))
		{
	
			$values = array('cat_id' => $request['cat_id'],'name' => $request['name'],'location' => $request['location'],'value' => $request['value'],'client' => $request['client'],'heading' => $request['heading'],'description' => $request['description'],'date_created' => date("Y-m-d H:i:s"), 'tags' => $request['tags']);
			$projectId = $GLOBALS['con']->insertInto('projects', $values)->execute();

			$response = '200';

			// insert Images
			if(!isset($request['images']))
				$request['images'] = array();
			 $this->addProjectImages($projectId, $request['images']);

			// insert Videos
			if(!isset($request['videos']))
				$request['videos'] = array();			 
			$this->addProjectVideos($projectId, $request['videos']);
		}
		else
		{
			$response = '400';
		}


			return array('code' => $response,'data' => $projectId);

	}

	public function addProjectImages($projectId, $images)
	{
		$response = 400;
		foreach($images as $image)
		{
			$values = array('project_id' => $projectId,'path' => $image);
			$query = $GLOBALS['con']->insertInto('project_images', $values)->execute();
			$response = 200;
		}		
		return $response;
	}

	public function addProjectVideos($projectId, $videos)
	{
		foreach($videos as $video)
		{
			$values = array('project_id' => $projectId,'embed_code' => $video,'source' => 'youtube');
			$query = $GLOBALS['con']->insertInto('project_videos', $values)->execute();
		}		
	}

	public function editProjectVideos($projectId, $videos)
	{
		foreach($videos as $video)
		{
			$values = array('project_id' => $projectId,'embed_code' => $video,'source' => 'youtube');
			$query = $GLOBALS['con']->update('project_videos', $values, $projectId)->execute();
		}		
	}

	public function editProject($request)
	{
		$response = 400;

		if(!empty($request['id']))
		{
			$video = $GLOBALS['con']->deleteFrom('project_videos')->where('project_id',$request['id'])->execute();
			$video = $GLOBALS['con']->deleteFrom('project_images')->where('project_id',$request['id'])->execute();
			$count = $GLOBALS['con']->from('projects')->where('id',$request['id'])->count();

			if($count > 0)
			{
				$values = array('cat_id' => $request['cat_id'],'name' => $request['name'],'location' => $request['location'],'value' => $request['value'],'client' => $request['client'],'heading' => $request['heading'],'description' => $request['description'],'date_created' => date("Y-m-d H:i:s"), 'tags' => $request['tags']);
				$query = $GLOBALS['con']->update('projects', $values, $request['id'])->execute();

				$response = 200;

				if(!isset($request['videos']))
					$request['videos'] = array();

				// add Videos
				$this->addProjectVideos($request['id'], $request['videos']);

				if(!isset($request['images']))
					$request['images'] = array();


				$this->addProjectImages($request['id'], $request['images']);
			}
			else
			{
				$response = 400;
			}
		}
		return $response;
	}


public function getVideos($project_id)
{

		// Initial response is bad request
		//$response = 400;

			
		$project = $GLOBALS['con']->from('project_videos')->where('project_id',$project_id);
		$data = array();

		foreach($project as $projects)
		    {
				$data[] = $projects;
			}

			$response = 200;
			
			return array('code' => $response,'data' => $data);
			
}

public function getImages($project_id)
{

		// Initial response is bad request
		//$response = 400;

		$project = $GLOBALS['con']->from('project_images')->where('project_id',$project_id);
		$data = array();

		foreach($project as $projects)
		    {
		    	$projects['web_url'] = Image::getRootPath(false).'data/project/'.$projects['path'];
				$data[] = $projects;
			}

			$response = 200;
			return array('code' => $response,'data' => $data);
			
}

public function getRelatedProjects($request)
{
	$project = $GLOBALS['con']->from('projects')->where('id',$request['id']);	
	if(!empty($project))
	{
		$tags = explode(',',$project['tags']);
		//$related_projects = $GLOBALS['con']->from ('projects')->where('tags' like $tags);
	}
}

}