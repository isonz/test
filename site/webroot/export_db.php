<?php
$host="127.0.0.1";      //数据库地址
$dbname="sanya";        //数据库名
$username="root";       //用户名
$passw="admin888";      //密码
$decode_key = 'en@sanya.com';
$decode_token = '274f6c49b3e31a0c1F3870BE274F6C49B3E31A0C6728957F';
$drop = true;

//---- auth
$sign = isset($_GET['sign']) ? $_GET['sign'] : '';
if(!$sign) exit('No permission! 001');
$decode = decode($sign, $decode_key);
if(!$decode) exit('No permission! 002');
$decode = explode(',', $decode);
$token = isset($decode[0]) ? $decode[0] : '';
if(!$token || $$token != $decode_token) exit('No permission! 003');
//---- end auth


error_reporting(E_ALL ^ E_DEPRECATED);
//ini_set("max_execution_time", 0);//避免数据量过大，导出不全的情况出现。
set_time_limit(0);

$filename=date("Y-m-d_H-i-s")."-".$dbname.".sql";
header("Content-disposition:filename=".$filename);//所保存的文件名
header("Content-type:application/octetstream");
header("Pragma:no-cache");
header("Expires:0");

//备份数据
$i = 0;
$crlf="\r\n";
global $dbconn;
$dbconn = mysql_connect($host,$username,$passw);	//数据库主机，用户名，密码
$db = mysql_select_db($dbname,$dbconn);
mysql_query("SET NAMES 'utf8'");
$tables =mysql_list_tables($dbname,$dbconn);
$num_tables = @mysql_numrows($tables);
print "-- filename=".$filename;
print $crlf.$crlf;
while($i < $num_tables)
{
    $table=mysql_tablename($tables,$i);
    echo get_table_structure($dbname, $table, $crlf)."$crlf";
    //echo get_table_def($dbname, $table, $crlf)."; $crlf";
    echo get_table_content($dbname, $table, $crlf);
    $i++;
}

/*新增的获得详细表结构*/
function get_table_structure($db,$table,$crlf)
{
    global $drop;

    $schema_create = "";
    $result =mysql_db_query($db, "SHOW CREATE TABLE $table");
    $row=mysql_fetch_array($result);
    $schema_create .= $crlf."-- ".$row[0].$crlf;
    if(!empty($drop)){ $schema_create .= "DROP TABLE IF EXISTS `$table`;$crlf";}
    $schema_create .= $row[1].';'.$crlf;
    Return $schema_create;
}

//获得表内容
function get_table_content($db, $table, $crlf)
{
    $schema_create = "";
    $temp = "";
    $result = mysql_db_query($db, "SELECT * FROM $table");
    $i = 0;
    while($row = mysql_fetch_row($result))
    {
        $schema_insert = "INSERT INTO `$table` VALUES (";
        for($j=0; $j<mysql_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= " NULL,";
            elseif($row[$j] != "")
                $schema_insert .= " '".addslashes($row[$j])."',";
            else
                $schema_insert .= " '',";
        }
        $schema_insert = ereg_replace(",$", "",$schema_insert);
        $schema_insert .= ");$crlf";
        $temp = $temp.$schema_insert ;
        $i++;
    }
    return $temp;
}

//原来别人的取得数据库结构，但不完整
function get_table_def($db,$table,$crlf)
{
    global $drop;

    $schema_create = "";
    if(!empty($drop))
        $schema_create .= "DROP TABLE IF EXISTS `$table`;$crlf";

    $schema_create .= "CREATE TABLE `$table` ($crlf";
    $result = mysql_db_query($db, "SHOW full FIELDS FROM $table");
    while($row = mysql_fetch_array($result))
    {
        $schema_create .= " `$row[Field]` $row[Type]";

        if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
            $schema_create .= " DEFAULT '$row[Default]'";
        if($row["Null"] != "YES")
            $schema_create .= " NOT NULL";
        if($row["Extra"] != "")
            $schema_create .= " $row[Extra]";
        if($row["Comment"] != "")
            $schema_create .= " Comment '$row[Comment]'";
        $schema_create .= ",$crlf";
    }
    $schema_create = ereg_replace(",".$crlf."$", "", $schema_create);
    $result = mysql_db_query($db, "SHOW KEYS FROM $table");
    while($row = mysql_fetch_array($result))
    {
        $kname=$row['Key_name'];
        if(($kname != "PRIMARY") && ($row['Non_unique'] == 0))
            $kname="UNIQUE|$kname";
        if(!isset($index[$kname]))
            $index[$kname] = array();
        $index[$kname][] = $row['Column_name'];
    }

    while(list($x,$columns) = @each($index))
    {
        $schema_create .= ",$crlf";
        if($x == "PRIMARY")
            $schema_create .= " PRIMARY KEY (".implode($columns,", ") . ")";
        elseif (substr($x,0,6) == "UNIQUE")
            $schema_create .= " UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
        else
            $schema_create .= " KEY $x (" . implode($columns, ", ") . ")";
    }

    $schema_create .= "$crlf)";
    return (stripslashes($schema_create));
}


function decode($tex, $key = "key123@ison")
{
    if(strlen($tex)<24)return false;
    $verity_str=substr($tex, 0,8);
    $tex=substr($tex, 8);
    if($verity_str!=substr(md5($tex),0,8)) return false;  //完整性验证失败

    $key_b = substr($tex,0,6);
    $rand_key = $key_b.$key;
    $rand_key=md5(md5($rand_key).$key_b);
    $key_p  = substr($rand_key, 0, 10);

    $texs=base64_decode(substr($tex, 6));
    $kp = substr($texs, 0,10);
    if($key_p !== $kp) return false;	//$key检验

    $tex = substr($texs, 10);
    $texlen=strlen($tex);
    $reslutstr="";
    for($i=0;$i<$texlen;$i++){
        $reslutstr.=$tex{$i}^$rand_key{$i%32};
    }
    return $reslutstr;
}


