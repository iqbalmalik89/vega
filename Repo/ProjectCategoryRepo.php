<?php 
class ProjectCategoryRepo{

	public function getProjectCategories($request)
		{
			// Initial response is bad request
			$response = 400;

			
			$project_cat 	= $GLOBALS['con']->from('project_categories');
			$data 			= array();

			foreach($project_cat as $items)
		    {
		    	$projectCatData = $this->getProjectCategory(array('id' => $items['id']));
				$data[] 		= $projectCatData['data'];
			}

			$response = 200;
			
			return array('code' => $response,'data' => $data);
			
		}

	public function getProjectCategory($request)
		{
			// Initial response is bad request
			$response = 400;

			$data = array();
			$project_cat = $GLOBALS['con']->from('project_categories')->where('id',$request['id']);

			foreach($project_cat as $items)
		    {
				$data = $items;
			}

			$response = 200;
			
			return array('code' => $response,'data' => $data);
			
		}

	public function deleteProjectCategory($request)
	{
		$id = $request['id'];
		$response = 400;

		if(!empty($id))
		{
			$exists 		= $GLOBALS['con']->from('project_categories')->where('id',$id)->count();
			if($exists)
			{
				$query 		= $GLOBALS['con']->deleteFrom('project_categories')->where('id', $id)->execute();
				$response 	= 200;
			}
			else
			{
				$response 	= 400;
			}
		}
		else
		{
			$response 	= 400;
		}
		return $response;

	}

	public function addProjectCategory($request)
	{

		$response = 400;
		if(!empty($request))
		{
			$count 			= $GLOBALS['con']->from('project_categories')->where('name',$request['name'])->count();
			if($count > 0)
			{
				$response 	= '400';
			}
			else
			{
				$values 	= array('name' => $request['name']);
				$query 		= $GLOBALS['con']->insertInto('project_categories', $values)->execute();			
				$response 	= 200;
			}
		}
		return $response;

	}

	public function editProjectCategory($request)
	{
		$response = 400;

		if(!empty($request['id']))
		{
			$count = $GLOBALS['con']->from('project_categories')->where('id',$request['id'])->count();

			if($count > 0)
			{
				$count 			= $GLOBALS['con']->from('project_categories')->where('name',$request['name'])->count();
				if($count > 0)
				{
					$response 	= '400';
				}
				else
				{
					$values 	= array('name' => $request['name']);
					$query 		= $GLOBALS['con']->update('project_categories', $values, $request['id'])->execute();			
					$response 	= 200;
				}
			}
			else
			{
				$response 	= 400;
			}
		}
		return $response;
	}

}
