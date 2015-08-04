<?php 
class ServicesRepo{

	public function getServices($request)
		{
			$requestData = $request;
			// Initial response is bad request
			//$response = 400;

			// If there is some data in json form
			
				$service = $GLOBALS['con']->from('services');
				$data = array();

				foreach($service as $services)
		    	{
		    		$servicesData 	= $this->getService(array('id' => $services['id']));
					$data[] 		= $servicesData['data'];

				}

				$response = 200;
					
			
			return array('code' => $response,'data' => $data);
			
	}

	public function getService($request)
	{
				$service = $GLOBALS['con']->from('services')->where('id',$request['id']);
				$data = array();

				foreach($service as $services)
		    	{
		    		$images = $this->getImages($request['id']);
		    		$services['images'] = $images['data']; 

		    		if(!empty($images['data']))
		    		{ 
		    			if(!empty($images['data'][0]))
		    			{
		    				$services['web_url_1'] = $images['data'][0]['web_url'];
		    			}
		    			if(!empty($images['data'][1]))
		    			{
		    				$services['web_url_2'] = $images['data'][1]['web_url'];
		    			}
		    		}

		    		$nameArr = explode(' ', $services['name']);

		    		if(!empty($nameArr))
		    		{
		    			$nameArr[0] = '<span class="id-color">'.$nameArr[0].'</span> ';
		    		}
		    		$services['short_description'] = substr($services['description'], 0, 270).'...';
		    		$services['decorated_name'] = implode(' ', $nameArr);

					$data 	= $services;

				}

				$response = 200;
			
			return array('code' => $response,'data' => $data);
			
	}

	public function deleteServices($request)
	{
		$id 		= $request['id'];
		$response 	= 400;

		if(!empty($id))
		{
			$exists 		= $GLOBALS['con']->from('services')->where('id',$id)->count();
			if($exists)
			{
				$query 		= $GLOBALS['con']->deleteFrom('services')->where('id', $id)->execute();
				$service 	= $GLOBALS['con']->deleteFrom('service_images')->where('service_id',$id)->execute();

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

	public function addServices($request)
	{

		if(!empty($request))
		{
			$name 			= $request['name'];
			$slug 			= $request['slug'];
			$description    = $request['description'];
			$archive    	= $request['archive'];
			
			// insert Images
			if(!isset($request['images']))
			$request['images'] = array();

			$count 			= $GLOBALS['con']->from('services')->where('slug',$slug)->count();
			if($count > 0)
			{
				$response 	= '400';
			}
			else
			{

				$values 		= array('name' => $name ,'slug' => $slug,'description' => $description, 'archive' => $archive);
				$serviceId 		= $GLOBALS['con']->insertInto('services', $values)->execute();

				$this->addServicesImages($serviceId, $request['images']);

				$response 	= '200';
			}

		}
		else
		{
			$response = '400';
		}


		return $response;

	}

	public function editServices($request)
	{

		if(!empty($request))
		{
			$response 		= '400';

			$id 			= $request['id'];
			$name 			= $request['name'];
			$slug 			= $request['slug'];
			$description    = $request['description'];
			$archive    	= $request['archive'];

			if(!empty($request['id']))
			{
				$count = $GLOBALS['con']->from('services')->where('id',$request['id'])->count();
				if($count >0) 
				{
					$service = $GLOBALS['con']->deleteFrom('service_images')->where('service_id',$request['id'])->execute();

						if(!isset($request['images']))
						$request['images'] = array();

						$this->addServicesImages($request['id'], $request['images']);

						$count 		= $GLOBALS['con']->from('services')->where('slug',$slug)->where('id != ?', $id)->count();
						if($count > 0)
							{
								$response 	= '400';
							}
							else
							{
								$values 	= array('id' => $id ,'name' => $name ,'slug' => $slug,'description' => $description, 'archive' => $archive);
								$query 		= $GLOBALS['con']->update('services', $values,$request['id'])->execute();

								$response 	= '200';
							}

					}
					else
					{
						$response 	= '400';
					}
				}

			}
		return $response;

	}

public function addServicesImages($serviceId, $images)
	{
		$response = 400;
		foreach($images as $image)
		{
			$values = array('service_id' => $serviceId,'path' => $image);
			$query = $GLOBALS['con']->insertInto('service_images', $values)->execute();
			$response = 200;
		}		
		return $response;
	}

public function getImages($service_id)
{

		// Initial response is bad request
		//$response = 400;

		$service = $GLOBALS['con']->from('service_images')->where('service_id',$service_id);
		$data = array();

		foreach($service as $services)
		    {
		    	$services['web_url'] = Image::getRootPath(false).'data/services/'.$services['path'];
				$data[] = $services;
			}

			$response = 200;
			return array('code' => $response,'data' => $data);
			
}

	
	public function getServiceDetail($slug)
	{
		$service = $GLOBALS['con']->from('services')->where('slug',$slug);
		$resp = array('service_data' => array(), 'services' => array());
		if(!empty($service))
		{
			$service = $service->fetch();
			$serviceDetail = $this->getService(array('id' => $service['id']));
			$resp['service_data'] = $serviceDetail['data'];
			$allServices = $this->getServices(array());
			$resp['services'] = $allServices['data'];
			
			if(!empty($resp['services']))
			{
				foreach ($resp['services'] as $key => &$singleService) {
					if($service['id'] == $singleService['id'])
					{
						$singleService['active'] = 'active';
					}
				}
			}
		}

		return $resp;

	}

}
