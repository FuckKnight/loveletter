<?php
    function post($url,$data)
    {
		$postdata = http_build_query($data);
		$opts = array('http' =>
			array(
				'method' => 'POST',
				'header' => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata
			)
		);
		$context = stream_context_create($opts);
		$result = file_get_contents($url, false, $context);
		return $result;
    }

	function whulib_json_decode($json)
	{
		$res = [];
		$array = json_decode($json,TRUE);
		foreach($array as $key=>$val)
		{
			$name = $val["name"];
			$value = $val["value"];
			$res[$name]=$value;
		}
		return $res;
	}

    function getVisitInfo($username) 
    {
		$res = whulib_json_decode(
			post("http://202.114.65.166/aleph-x/stat/query", 
				['BorForm' => 
					['username'=>'byj',
					'password'=>'xxzx2017byj',
					'op'=>'bor-visit-info',
					'bor_id'=>$username,
					'op_param'=>'',
					'op_param2'=>'',
					'op_param3'=>'']
        ]));
        if(isset($res["error"]))
			return null;
		var_dump($res);
		return ($res["visit-count"] == 0) ? null : $res;
	}

    function getLoanInfo($username) 
    {
		$res = whulib_json_decode(
			post("http://202.114.65.166/aleph-x/bor/oper", 
				['BorForm' => 
					['username'=>'byj',
					'password'=>'xxzx2017byj',
					'op'=>'loan-history',
					'bor_id'=>$username,
					'op_param'=>'',
					'op_param2'=>'',
					'op_param3'=>'']
		]));
		if(isset($res["error"]))
			return null;
		return $res;
	}		

    function getInfo($username) 
    {
		$res['visit'] = getVisitInfo($username);
		if($res['visit'] == null) return null;
		$res['loan'] = getLoanInfo($username);
		return $res;
	}

	function getDetail($username)
	{
		$res = json_decode(
			post("http://202.114.65.166/aleph-x/bor/oper",
					['BorForm' =>
						['username'=>'byj',
						'password'=>'xxzx2017byj',
						'op'=>'loan-history-detail',
						'bor_id'=>$username,
						'op_param'=>'',
						'op_param2'=>'',
						'op_param3'=>''
			]]),TRUE);
		if(isset($res["error"]))
			return null;
		return $res;
	}
?>
