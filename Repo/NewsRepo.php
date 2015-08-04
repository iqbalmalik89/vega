<?php 
class NewsRepo{

	public function getNews($request)
		{
			$requestData = $request;
			// Initial response is bad request
			//$response = 400;

			// If there is some data in json form
			
				$new = $GLOBALS['con']->from('news')->orderBy('date_created','DESC');
				$data = array();

				foreach($new as $news)
		    	{
		    		$newsData 		= $this->getNew(array('id' => $news['id']));
					$data[] 		= $newsData['data'];

				}

				$response = 200;
					
			
			return array('code' => $response,'data' => $data);
			
	}

	public function getNew($request)
	{
				$new = $GLOBALS['con']->from('news')->where('id',$request['id']);

				foreach($new as $news)
		    	{
					$news['web_url'] 	= Image::getRootPath(false).'data/news/'.$news['path'];
					$data 				= $news;

				}

				$response = 200;
			
			return array('code' => $response,'data' => $data);
			
	}

	public function deleteNews($request)
	{
		$id 		= $request['id'];
		$response 	= 400;

		if(!empty($id))
		{
			$exists 		= $GLOBALS['con']->from('news')->where('id',$id)->count();
			if($exists)
			{
				$query 		= $GLOBALS['con']->deleteFrom('news')->where('id', $id)->execute();
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

	public function addNews($request)
	{

		if(!empty($request))
		{
			$heading 			= $request['heading'];
			$description 		= $request['description'];
			$path    			= $request['path'];
			$archive    		= $request['archive'];

			$values 	= array('heading' => $heading,'description' => $description,'path' => $path, 'archive' => $archive, 'date_created' => date("Y-m-d H:i:s"));
			$query 		= $GLOBALS['con']->insertInto('news', $values)->execute();

			$response 	= '200';

		}
		else
		{
			$response = '400';
		}


		return $response;

	}

	public function editNews($request)
	{

		if(!empty($request))
		{
			$id 				= $request['id'];
			$heading 			= $request['heading'];
			$description 		= $request['description'];
			$path    			= $request['path'];
			$archive    		= $request['archive'];

			$values 	= array('id' => $id ,'heading' => $heading,'description' => $description,'path' => $path, 'archive' => $archive, 'date_created' => date("Y-m-d H:i:s"));
			$query 		= $GLOBALS['con']->update('news', $values,$request['id'])->execute();

			$response 	= '200';

		}
		else
		{
			$response 	= '400';
		}


		return $response;

	}


}
