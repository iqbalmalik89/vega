<?php 
class TestimonialRepo{

	public function getTestimonials($request)
	{
		// Initial response is bad request
		$response = 400;

		$testimonials = $GLOBALS['con']->from('testimonials');
		$data = array();

		foreach($testimonials as $testimonial)
		{
			$testimonialData 	= $this->getTestimonial(array('id' => $testimonial['id']));
			$data[] 			= $testimonialData['data'];
		}

		$response = 200;
			
		return array('code' => $response,'data' => $data);
			
	}

	public function getTestimonial($request)
	{
		// Initial response is bad request
		$response = 400;

		$testimonial = $GLOBALS['con']->from('testimonials')->where('id',$request['id'])->fetch();

		$testimonial['web_url'] = Image::getRootPath(false).'data/testimonial/'.$testimonial['path'];
		$data = $testimonial;

		$response = 200;
			
		return array('code' => $response,'data' => $data);
			
	}

	public function deleteTestimonial($request)
	{
		$id 		= $request['id'];
		$response 	= 400;

		if(!empty($id))
		{
			$exists 		= $GLOBALS['con']->from('testimonials')->where('id',$id)->count();
			if($exists)
			{
				$query 		= $GLOBALS['con']->deleteFrom('testimonials')->where('id', $id)->execute();
				$response 	= 200;
			}
			else
			{
				$response 	= 400;
			}
		}
		else
		{
			$response 		= 400;
		}
		return $response;

	}

	public function addTestimonial($request)
	{
		$response 		= 400;

		if(!empty($request))
		{
	
			$values 	= array('testimonial' => $request['testimonial'],'client_name' => $request['client_name'],'company_name' => $request['company_name'], 'path' => $request['path']);
			$query 		= $GLOBALS['con']->insertInto('testimonials', $values)->execute();


			$response 	= '200';
		}
		else
		{
			$response 	= '400';
		}


		return $response;

	}

	public function editTestimonial($request)
	{
		$response 		= 400;

		if(!empty($request['id']))
		{
			$count 		= $GLOBALS['con']->from('testimonials')->where('id',$request['id'])->count();

			if($count > 0)
			{
				$values	 = array('testimonial' => $request['testimonial'],'client_name' => $request['client_name'],'company_name' => $request['company_name'], 'path' => $request['path']);
				$query 	 = $GLOBALS['con']->update('testimonials', $values, $request['id'])->execute();

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