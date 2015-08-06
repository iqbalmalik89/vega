<?php 
class QueryRepo{

	public function getQueries($request)
		{
			$requestData = $request;
			// Initial response is bad request
			//$response = 400;

			// If there is some data in json form
			

				$limit = 8;
				$total_pages = 0;
				if(!isset($request['page']))
					$page = 0;
				else
					$page = $request['page'];

				$offset = $page * $limit;
				$count = $GLOBALS['con']->from('queries')->count();
				$total_pages = ceil($count / $limit) ;			

				$query = $GLOBALS['con']->from('queries')->limit($limit)->offset($offset);
				$data = array();

				foreach($query as $items)
		    	{
		    		$queryData = $this->getQuery(array('id' => $items['id']));
					$data[] = $queryData['data'];

				}
		$total_pages = $total_pages;

				$response = 200;
					
			
			return array('code' => $response,'data' => $data, 'total_pages' => $total_pages);
			
	}

	public function getQuery($request)
	{
			// Initial response is bad request
			//$response = 400;

				$query = $GLOBALS['con']->from('queries')->where('id',$request['id']);

				foreach($query as $queries)
		    	{
					$data = $queries;

				}

				$response = 200;
			
			return array('code' => $response,'data' => $data);
			
	}

	public function deleteQuery($request)
	{
		$id = $request['id'];
		$response = 400;

		if(!empty($id))
		{
			$exists = $GLOBALS['con']->from('queries')->where('id',$id)->count();
			if($exists)
			{
				$query = $GLOBALS['con']->deleteFrom('queries')->where('id', $id)->execute();
				$response = 200;
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

	public function addQuery($request)
	{

		if(!empty($request))
		{
			$name 				= $request['name'];
			$email 				= $request['email'];
			$phone				= $request['phone'];
			$subject            = $request['subject'];
			$message    		= $request['message'];


			$values = array('name' => $name,'email' => $email,'`phone`' => $phone , '`subject`' => $subject,	'`message`' => $message, 'date_created' => date("Y-m-d H:i:s"));
			$query = $GLOBALS['con']->insertInto('queries', $values)->execute();

			$loginRepo = new LoginRepo();
			$admindata = $loginRepo->getAdminData(1);

			$to = $admindata['username'];

			$msg = "<html>
					<head>
					  <title>New Contact Query </title>
					</head>
					<body>
						<p>".$name." sent you a message.</p>

						<tr>
						<th>Subject: ".$subject."</th>
						<th>Name: ".$name."</th>
						<th>Email: ".$email."</th>
						<th>Phone: ".$phone."</th>
						<th>Message: ".$message."</th>
						</tr>
					    
					</body>
					</html>";
			$subject = "Contact Query";

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			//$headers .= 'From: <'. .'>' . "\r\n";

			$email = mail($to,$subject,$msg,$headers);	

			$response = '200';

		}
		else
		{
			$response = '400';
		}


		return $response;

	}

	public function editQuery($request)
	{

		if(!empty($request))
		{
			$id 				= $request['id'];
			$name 				= $request['name'];
			$email 				= $request['email'];
			$phone				= $request['phone'];
			$message    		= $request['message'];


			$values = array('id' => $id , 'name' => $name,'email' => $email,'`phone`' => $phone ,	'`message`' => $message, 'date_created' => date("Y-m-d H:i:s"));
			$query = $GLOBALS['con']->update('queries', $values,$request['id'])->execute();

			$response = '200';

		}
		else
		{
			$response = '400';
		}


		return $response;

	}

}
