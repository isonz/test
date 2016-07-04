<?php
/*
$array = array(
	array("person"=>array("name"=>"ison", "age"=>23, "sex"=>"男")),
	array("person"=>array("name"=>"katie", "age"=>33, "sex"=>"女")),
	array("person"=>array("name"=>"chen", "age"=>45, "sex"=>"男")),
	array("person"=>array("name"=>"yan", "age"=>21, "sex"=>"女"))
);

$xml = <<<XML
<?xml version='1.0' encoding='utf-8'?>
<persons>
</persons>
XML;
$xml = simplexml_load_string($xml);
foreach ($array as $k => $data) {
    //$item = $xml->addChild($k);
    if (is_array($data)) {
        foreach ($data as $keyy => $rows) {
        	$item = $xml->addChild($keyy);
        	if (is_array($rows)) {
	        	foreach ($rows as $key => $row) {
		          $node = $item->addChild($key, $row);
	        	}
        	}else{
        		$node = $item->addChild($keyy, $rows);
        	}
        }
    }
}
echo $xml->asXML();
*/
header("Content-type:text/xml");
exit('<?xml version="1.0" encoding="UTF-8"?>
<persons>
<person id="1"><name>ison</name><age>23</age><sex>男</sex></person>
<person id="2"><name>katie</name><age>33</age><sex>女</sex></person>
<person id="3"><name>chen</name><age>45</age><sex>男</sex></person>
<person id="4"><name>yan</name><age>21</age><sex>女</sex></person>
</persons>');
?>
