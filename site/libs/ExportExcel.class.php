<?php
/****************************************
 * 导出excel文件  
 *  $info = array(array(),array());
 *  $excel = new ExportExcel();
	$excel->ExportExcel('utf-8', 'gb2312');
	$title_row=array('序号', '姓名', '部门', '签到账号', '签到时间','是否有效','备注');
	$excel->addOneRow($title_row);
	foreach($info as $key => $val){			
		$excel->addOneRow($val);
	}
	$excel->outPut('1.xls', 'gb2312');
****************************************/
class ExportExcel {
	var $file_name = ''; //out put file name
	var $buffer = '';    //out put content
	var $source_charset = 'utf-8'; //源数据编码格式
	var $output_file_charset = 'gb2312'; //输出文件的编码格式
	var $line = 0; //记录当前添加数据的行号
	function ExportExcel($source_charset='', $output_file_charset='') {
		$this->source_charset = empty($source_charset) ? $this->source_charset : $source_charset;
		$this->output_file_charset = empty($output_file_charset) ? $this->output_file_charset : $output_file_charset;
		$this->setBOF();   // begin Excel stream
	}
	// ----- begin of function library -----
	// Excel begin of file header
	protected function setBOF() {
		$this->buffer .= pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);  
		return;
	}
	// Excel end of file footer
	protected function setEOF() {
		$this->buffer .= pack("ss", 0x0A, 0x00);
		return;
	}
	// Function to write a Number (double) into Row, Col
	protected function addNumber($Row, $Col, $Value) {
		$this->buffer .= pack("sssss", 0x203, 14, $Row, $Col, 0x0);
		$this->buffer .= pack("d", $Value);
		return;
	}
	// Function to write a label (text) into Row, Col
	protected function addText($Row, $Col, $Value ) {
		//转换编码
		if ($this->source_charset != $this->output_file_charset) {
		    $Value = iconv($this->source_charset, $this->output_file_charset, $Value);
		}
		$L = strlen($Value);
		$this->buffer .= pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
		$this->buffer .= $Value;
	    return;
	}
	//添加一行数据(以数组形式传入)
	/*
	 * array $arr 数组，其中数据索引值为列号，元素的值则为其值
	 */
	function addOneRow($arr) {
		if (!empty($arr)) {
			$index_arr = array_values($arr);
			$len = count(array_values($index_arr));
			for ($i=0; $i<$len; $i++) {
				if (is_numeric($index_arr[$i])) { //数字型值
				    $this->addNumber($this->line, $i, $index_arr[$i]);
				} else {                          //文本类型
				    $this->addText($this->line, $i, $index_arr[$i]);
				}
			}
			$this->line++;
		} else {
		    return false;
		}
	}
	//添加多行数据(以数组形式传入)
	/*
	 * array $arr 二维数组
	 */
	function addRows($arr) {
		if (!empty($arr)) {
			foreach($arr as $v) {
				$this->addOneRow($v);
			}
			$this->line++;
		} else {
		    return false;
		}
	}
	//out put the file
	function outPut($file_name='attachment.xls', $charset='gb2312') {
		//
		// the next lines demonstrate the generation of the Excel stream
		$this->setEOF(); // close the stream		
		//  
		// To display the contents directly in a MIME compatible browser  
		// add the following lines on TOP of your PHP file:		
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");     
		header ("Pragma: no-cache");     
		header ("Content-type: application/x-msexcel;charset={$charset}");
		header ("Content-Disposition: attachment; filename={$file_name}" );  
		header ("Content-Description: q@welltao.com" );
		exit($this->buffer);
	}
	// ----- end of function library -----
}