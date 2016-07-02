<?php
class Paging
{
	private static $_data = null;
	private static $_total = 0;
	private static $_page = 0;
	private static $_page_siez = 0;
	private static $_total_page = 0;
	
	static function bySQL($sql, $page, $page_size=50, $as='')
	{
		$data = self::getSQLData($sql, $page, $page_size, $as);
		$paging = self::pagedShow();
		$result['data'] = $data;
		$result['page'] = $paging;
		return $result;
	}
	
	static public function getSQLData($sql, $page=1, $page_size = 0, $as='')
	{
		if(!$sql) return array();
	
		$total = self::getSQLCounts($sql, $as);		
		if(!$page_size) $page_size = 30;
		$total_page = ceil($total/$page_size);
		if($page < 1) $page = 1;
		if($page > $total_page) $page = $total_page;
		if($page < 1) $page = 1;
	
		self::$_page = $page;
		self::$_page_siez = $page_size;
		self::$_total_page = $total_page;
		$count = ($page-1) * $page_size;

		$sql = $sql . " LIMIT $count,$page_size";
		$data = DB::GetQueryResult($sql, false);
		self::$_data = $data;
		return $data;
	}
	
	static public function counts()
	{
		return self::$_total;
	}
	
	static public function getSQLCounts($sql, $as='')
	{
		if(!$sql) return false;
		$select = stristr($sql, 'FROM', true) . 'FROM';
		if(!$as){
			$sql = str_replace($select, "SELECT count(id) AS counts FROM", $sql);
		}else{
			$sql = str_replace($select, "SELECT count($as.id) AS counts FROM", $sql);
		}
		//DB::Debug();
		$rs = DB::GetQueryResult($sql, false);
		self::$_total = isset($rs[0]['counts']) ? (int)$rs[0]['counts'] : 0;
		return self::$_total;
	}
	
	static public function getData($tableName, $page=1, $page_size = 0, $where = null, $order = null, $select = null)
	{
		if(!$tableName) return false;
		
		$total = self::getCounts($tableName, $where);
		if(!$page_size) $page_size = 30;
		$total_page = ceil($total/$page_size);
		if($page < 1) $page = 1;
		if($page > $total_page) $page = $total_page;
		if($page < 1) $page = 1;

		self::$_page = $page;
		self::$_page_siez = $page_size;
		self::$_total_page = $total_page;
		
		$count = ($page-1) * $page_size;

		if(!$where) $where = "id>0";
		if(!$order){
			$order = " ORDER BY id DESC";
		}else{
			$order = " ORDER BY " . $order;
		}
		if(!$select) $select = '*';
		
		$options['condition'] = $where;
		$options['offset'] = $count;
		$options['size'] = $page_size;
		$options['order'] = $order;
		$options['select'] = $select;
		//DB::Debug();
		$data = DB::LimitQuery($tableName, $options);
		self::$_data = $data;
		return $data;
	}
	
	static public function getCounts($tableName, $where = null)
	{
		if(!$tableName) return false;	
		if($where) $where = " WHERE $where";
		$sql = "SELECT count(id) AS counts FROM ". $tableName . $where;
        $rs = DB::GetQueryResult($sql, false);
		self::$_total = isset($rs[0]['counts']) ? (int)$rs[0]['counts'] : 0;
		return self::$_total;
	}
	
	static public function getPage()
	{
		$total = self::$_total;
		$page = self::$_page;
		$page_size = self::$_page_siez;
		$total_page = self::$_total_page;

		if($total_page < 2) return '';
		
		$pre_page = $page - 1;
		$next = $page + 1;
		
		if($pre_page < 1) $pre_page = 1;
		if($next > $total_page) $next = $total_page;
		
		$paging = '<form action="">';
		$paging .= 'Total count:' . $total;
		$paging .= '&nbsp;&nbsp;';
		$paging .= 'Total page:'. $total_page;
		$paging .= '&nbsp;&nbsp;';
		$paging .= 'Current page:' . $page;
		$paging .= '&nbsp;&nbsp;';
		$paging .= '<a href="?page='. $pre_page .'&size='.$page_size.'">Previous</a>';
		$paging .= '&nbsp;&nbsp;';
		$paging .= '<a href="?page='. $next .'&size='.$page_size.'">Next</a>';
		$paging .= '&nbsp;&nbsp;';
		$paging .= 'Jump to:<input type="text" name="page" value="'. $page .'" size="5" maxlength="10" /> <input type="hidden" name="size" value="'. $page_size .'" size="5" maxlength="10" /> &nbsp;&nbsp;<input type="submit" value="Submit">';
		$paging .= '</form>';
		
		return $paging;
	}
	
	static public function showPaging($total = 0, $page = 1, $page_size = 0)
	{
		if($total < 1) return '';
		
		if(!$page_size) $page_size = 30;
		$total_page = ceil($total/$page_size);
		if($page < 1) $page = 1;
		if($page > $total_page) $page = $total_page;
		
		if($total_page < 2) return '';
		
		$pre_page = $page - 1;
		$next = $page + 1;
		
		if($pre_page < 1) $pre_page = 1;
		if($next > $total_page) $next = $total_page;
		
		$paging = '<form action="">';
		$paging .= 'Total count:' . $total;
		$paging .= '&nbsp;&nbsp;';
		$paging .= 'Total page:'. $total_page;
		$paging .= '&nbsp;&nbsp;';
		$paging .= 'Current page:' . $page;
		$paging .= '&nbsp;&nbsp;';
		$paging .= '<a href="?page='. $pre_page .'">Previous</a>';
		$paging .= '&nbsp;&nbsp;';
		$paging .= '<a href="?page='. $next .'">Next</a>';
		$paging .= '&nbsp;&nbsp;';
		$paging .= 'Jump to:<input type="text" name="page" value="'. $page .'" size="5" maxlength="10" />&nbsp;&nbsp;<input type="submit" value="Submit">';
		$paging .= '</form>';
		
		return $paging;
	}
	
	//块状按钮显示页码
	static public function pagedShow($shownum=9, $is_arr=false)
	{
		$total = self::$_total;
		$page = self::$_page;
		$page_size = self::$_page_siez;
		$total_page = self::$_total_page;
		
		$pre_page = $page - 1;
		$next = $page + 1;
		
		if($pre_page < 1) $pre_page = 1;
		if($next > $total_page) $next = $total_page;
		
		$shownum = (int)$shownum;
		if($total_page < 1) $total_page = 1;
		if($page < 1) $page = 1;
		if($page > $total_page) $page = $total_page;
		if($page_size < 1) $page_size = 1;
		if($shownum < 3) $shownum = 3;
		if($shownum > $total_page) $shownum = $total_page;
	
		//-------url
		$cururl = Func::getCurrentURL();
		$pos = strpos($cururl, '?');
		if(!$pos){
			$cururl = $cururl.'?';
		}else{
			$urlpas = explode('?', $cururl);
			if(isset($urlpas[1])) $cururl = $urlpas[1];
			$urlpara = Func::convertUrlQuery($cururl);
			if(isset($urlpara['page'])) unset($urlpara['page']);
			if(isset($urlpara['pagesize'])) unset($urlpara['pagesize']);
			if(count($urlpara) >= 1){
				$cururl = Func::getUrlQuery($urlpara);
				if(isset($urlpas[1])) $cururl = $urlpas[0]."?".$cururl.'&';
			}else{
				$cururl = '';
				if(isset($urlpas[1])) $cururl = $urlpas[0]."?".$cururl;
			}
		}
		//------ end url
		
		$arr[] = array();
		$active = '';
		$paging = '<style>#paged{margin:30px 0; text-align:center;}#paged ul{margin:10px auto; text-align:center;}';
		$paging .= '#paged ul li{margin:10px; height:42px; width:42px; display:inline-block; float:none;}';
		$paging .= '#paged ul li a{display:block; line-height:40px; border: 1px #ccc solid;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;}';
		$paging .= '#paged ul li a:hover,#paged ul li a.active{background:#4ba746; color:#FFF;}</style>';
		
		$paging .= '<ul class="clearfix">';
		if($total_page<=$shownum){
			for($i=1; $i<=$shownum; $i++){
				$arr[] = $i;
				if($page==$i){$active=' class="active"';}else{$active='';}
				$paging .= "<li><a href='".$cururl."page=$i&pagesize=$page_size'$active>$i</a></li>";
			}
		}else if($page<=ceil($shownum/2) && $total_page>$shownum){
			for($i=1; $i<=$shownum; $i++){
				$arr[] = $i;
				if($page==$i){$active=' class="active"';}else{$active='';}
				$paging .= "<li><a href='".$cururl."page=$i&pagesize=$page_size'$active>$i</a></li>";
			}
			$arr[] = ">>";
			$paging .= "<li><a href='".$cururl."page=".($total_page-1)."&pagesize=$page_size'>>></a></li>";
			$arr[] = $total_page;
			if($page==$total_page){$active=' class="active"';}else{$active='';}
			$paging .= "<li><a href='".$cururl."page=$total_page&pagesize=$page_size'$active>$total_page</a></li>";
		}else if($page > ceil($shownum/2) && $page < ceil($total_page-ceil($shownum/2)) && $total_page>$shownum){
			$arr[] = 1;
			if($page==1){$active=' class="active"';}else{$active='';}
			$paging .= "<li><a href='".$cururl."page=1&pagesize=$page_size'$active>1</a></li>";
			$arr[] = "<<";
			$paging .= "<li><a href='".$cururl."page=2&pagesize=$page_size'><<</a></li>";
			for($i=$page-floor($shownum/2); $i<=($page+floor($shownum/2)); $i++){
				$arr[] = $i;
				if($page==$i){$active=' class="active"';}else{$active='';}
				$paging .= "<li><a href='".$cururl."page=$i&pagesize=$page_size'$active>$i</a></li>";
			}
			$arr[] = ">>";
			$paging .= "<li><a href='".$cururl."page=".($total_page-1)."&pagesize=$page_size'>>></a></li>";
			$arr[] = $total_page;
			if($page==$total_page){$active=' class="active"';}else{$active='';}
			$paging .= "<li><a href='".$cururl."page=$total_page&pagesize=$page_size'$active>$total_page</a></li>";
		}else if(($page+ceil($shownum/2))>=$total_page && $total_page>$shownum){
			$arr[] = 1;
			if($page==1){$active=' class="active"';}else{$active='';}
			$paging .= "<li><a href='".$cururl."page=1&pagesize=$page_size'$active>1</a></li>";
			$arr[] = "<<";
			$paging .= "<li><a href='".$cururl."page=2&pagesize=$page_size'><<</a></li>";
			for($i=$page-floor($shownum/2); $i<=$total_page; $i++){
				$arr[] = $i;
				if($page==$i){$active=' class="active"';}else{$active='';}
				$paging .= "<li><a href='".$cururl."page=$i&pagesize=$page_size'$active>$i</a></li>";
			}
		}
		$paging .= "</ul> 总记录数 : $total";
		if($is_arr) return $arr;
		return $paging;
	}
	
}
