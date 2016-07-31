<?php
/**
 * 仿写CodeIgniter的FTP类
 * $GLOBALS['CONFIG_FTP']=array('server'=>"smtp.xxx.com",'user'=>"",'passwd'=>"",'port'=>21,'passive'=>true,'timeout'=>90);
 * @Auth : ison.zhang
 */
class Ftp
{
    static private $_debug  = false;
    static private $_conn_id  = false;
    static private $_login = false;
    static private $_lstart_path = '';
    static private $_rstart_path = '/';

    //初始化
    static private function _init()
    {
        if(!self::$_login || !self::$_conn_id || !is_resource(self::$_conn_id)) {
            $config = $GLOBALS['CONFIG_FTP'];
            $config['server'] = preg_replace('|.+?://|', '', $config['server']); //特殊字符过滤
            $config['port'] = isset($config['port']) ? (int)$config['port'] : 21;
            $config['passive'] = isset($config['passive']) ? $config['passive'] : false;
            $config['timeout'] = isset($config['timeout']) ? (int)$config['timeout'] : 90;
            self::_connect($config);
        }
        return self::$_login;
    }

    // FTP连接 @return boolean
    static private function _connect($config)
    {
        self::$_conn_id = @ftp_connect($config['server'], $config['port'], $config['timeout']);
        if(!self::$_conn_id){
            if(self::$_debug) self::log("ftp unable to connect: ".json_encode($config));
            return false;
        }

        self::$_login = @ftp_login(self::$_conn_id, $config['user'], $config['passwd']);
        if(!self::$_login) {
            if(self::$_debug) self::log("ftp unable to login: ".json_encode($config));
            return false;
        }

        $passive = $config['passive'];
        if($passive) @ftp_pasv(self::$_conn_id, true);

        return self::$_login;
    }

    //从文件名中获取后缀扩展 @return string
    static private function _getFileType($filename)
    {
        if(!$filename) return false;
        if(false === strrpos($filename, '.')) return 'txt';
        $extarr = explode('.', $filename);
        return end($extarr);
    }

    //从后缀扩展定义FTP传输模式  ascii 或 binary  @return string
    static private function _setModeType($ext)
    {
        if(!$ext) return false;
        $text_type = array('txt','text','php','phps','php4','js','css','htm','html','phtml','shtml','log','xml');
        return (in_array($ext, $text_type)) ? 'ascii' : 'binary';
    }

    //切换 FTP 服务器上的当前目录。$supdebug:是否调试，@return boolean
    static public function chdir($path = '/', $supdebug = false)
    {
        if(!self::_init()) return false;
        if(!$path) return false;

        $result = @ftp_chdir(self::$_conn_id, $path);
        if($result) return true;

        if(self::$_debug && $supdebug) self::log("ftp unable to chdir:dir: $path");
        return false;
    }

    //目录生成, $permissions文件权限(eg:0644,0755), @return boolean
    static public function mkdir($path, $permissions='0644')
    {
        if(!self::_init()) return false;
        if(!$path) return false;
        if(!$permissions) $permissions = '0644';

        $result = @ftp_mkdir(self::$_conn_id, $path);
        self::chmod($path, $permissions);
        if($result) return true;

        if(self::$_debug) self::log("ftp_unable_to_mkdir: $path");
        return false;
    }

    //上传:本地文件, 远程文件名, 上传模式:auto,ascii, 上传后的文件权限列表 @return boolean
    //$put = Ftp::put("c:\\1.txt", '/1.txt', 'auto', '0644');
    static public function put($local_file , $remote_file, $mode='auto', $permissions='0664', $resume=0)
    {
        if(!self::_init()) return false;
        if(!$local_file || !$remote_file) return false;

        if(!file_exists($local_file)) {
            if(self::$_debug) self::log("ftp_no_local_file: $local_file");
            return false;
        }
        $resume = (int)$resume;
        if($resume < 0) $resume = 0;

        if($mode == 'auto') {
            $ext = self::_getFileType($local_file);
            $mode = self::_setModeType($ext);
        }
        $mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;

        $result = @ftp_put(self::$_conn_id, $remote_file, $local_file, $mode, $resume);
        self::chmod($remote_file, $permissions);
        if($result) return true;

        if(self::$_debug) self::log("ftp_unable_to_upload:localpath[$local_file],remotepath[$remote_file]");
        return false;
    }

    //下载: 远程目录标识(ftp), 本地目录标识, $replace是否覆盖本地已有文件，下载模式auto,ascii @return boolean
    //$get = Ftp::get('/1.txt', 'E:\\2.txt');
    static public function get($remotepath, $localpath, $replace=true, $mode = 'auto', $resume=0)
    {
        if(!self::_init()) return false;
        if(!$localpath || !$remotepath) return false;
        if(!$replace && file_exists($localpath)) {
            if(self::$_debug) self::log("ftp file_exists: $localpath");
            return false;
        }

        $resume = (int)$resume;
        if($resume < 0) $resume = 0;
        if($mode == 'auto') {
            $ext = self::_getFileType($remotepath);
            $mode = self::_setModeType($ext);
        }
        $mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;

        $result = @ftp_get(self::$_conn_id, $localpath, $remotepath, $mode, $resume);
        if($result) return true;

        if(self::$_debug) self::log("ftp_unable_to_download:localpath[$localpath]-remotepath[$remotepath]", 'ftp_get_');
        return false;
    }

    //重命名/移动远程文件: 远程目录标识(ftp),新目录标识,判断是重命名(FALSE)还是移动(TRUE) @return boolean
    static public function rename($oldname, $newname, $move=false)
    {
        if(!self::_init()) return false;
        if(!$oldname || !$newname) return false;

        $result = @ftp_rename(self::$_conn_id, $oldname, $newname);
        if($result) return true;

        if(self::$_debug){
            $msg = ($move == FALSE) ? "ftp_unable_to_rename" : "ftp_unable_to_move";
            self::log($msg);
        }
        return false;
    }

    //删除文件: 文件标识(ftp) @return boolean
    static public function delete($file)
    {
        if(!self::_init()) return false;
        if(!$file) return false;

        $result = @ftp_delete(self::$_conn_id, $file);
        if($result) return true;

        if(self::$_debug) self::log("ftp_unable_to_delete_file:file[$file]");
        return false;
    }

    //删除文件夹: 目录标识(ftp) @return boolean
    //$delete = Ftp::rmdir('/test/');
    static public function rmdir($path)
    {
        if(!self::_init()) return false;
        if(!$path) return false;

        $path = preg_replace("/(.+?)\/*$/", "\\1/", $path);  //对目录宏的'/'字符添加反斜杠'\'
        $filelist = self::filelist($path);
        if(!$filelist || count($filelist) < 1) return false;

        foreach($filelist as $item){       //如果我们无法删除,那么就可能是一个文件夹
            if(!self::delete($item)){
                self::rmdir($item);         //所以我们递归调用delete_dir()
            }
        }
        $result = @ftp_rmdir(self::$_conn_id, $path);    //删除文件夹(空文件夹)
        if($result) return true;

        if(self::$_debug) self::log("ftp_unable_to_delete_dir:dir[$path]");
        return false;
    }

    //获取当前目录下所有文件 : Ftp::getAllArray('/');
    static public function getAllArray($path)
    {
        if(!self::_init()) return array();
        if(!$path) return array();

        $filelist = self::filelist($path);
        if(!$filelist || count($filelist) < 1) return array();

        static $allFiles = array();
        foreach($filelist as $currentFile) {
            if (self::isDir($currentFile)) self::getAllArray($currentFile);
            $allFiles[$path][] = substr($currentFile, strlen($path));
        }
        return $allFiles;
    }

   	//下载当前目录下所有文件 : Ftp::getAll('/work/fun', '/www/Bak/site', true, 0777, array('/test'));
    static public function getAll($remotepath, $localpath, $replace=true, $permissions=0777, $exclude=array(), $mode = 'auto', $resume=0, $root=true)
    {
        if(!self::_init()) return false;
        if(!$localpath || !$remotepath) return false;
        if(!is_dir($localpath)) mkdir($localpath, $permissions, true);

        $filelist = self::filelist($remotepath);
        if(!$filelist || count($filelist) < 1) return array();

        if($root) self::$_lstart_path = $localpath;
        foreach($filelist as $currentFile) {
			if(in_array($currentFile, $exclude)) continue;
            if (self::isDir($currentFile)){
                $locpath = self::$_lstart_path . $currentFile;
                self::getAll($currentFile, $locpath, true, $permissions, $exclude, $mode, $resume, false);
                continue;
            }

			$local_file = self::$_lstart_path.$currentFile;
            if(!$replace && file_exists($local_file)) {
                if(self::$_debug) ModsBase::log("ftp file_exists: $local_file");
                return false;
            }
            $local_dir = dirname($local_file);
            if(!is_dir($local_dir)) mkdir($local_dir, $permissions, true);

            $resume = (int)$resume;
            if($resume < 0) $resume = 0;
            if($mode == 'auto') {
                $ext = self::_getFileType($remotepath);
                $mode = self::_setModeType($ext);
            }
            $mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;
            self::get($currentFile, $local_file, $replace, $mode, $resume);
        }
        return true;
    }

    //判断目录是否文件夹
    static public function isDir($path)
    {
        if(!self::_init()) return false;
        if(!$path) return false;
        $is = self::chdir($path);
        if($is){
            self::chdir('..');
            return true;
        }
        return false;
    }

    // 修改文件权限 @return boolean
    static public function chmod($path, $perm)
    {
        if(!self::_init()) return false;
        if(!$path) return false;

        if(!function_exists('ftp_chmod')) {
            if(self::$_debug) self::log("ftp unable to chmod( ftp_chmod )");
            return false;
        }
        $result = @ftp_chmod(self::$_conn_id, $perm, $path);
        if($result) return true;

        if(self::$_debug) self::log("ftp_unable_to_chmod:path[$path]-chmod[$perm]");
        return false;
    }

    //获取目录文件列表: 目录标识(ftp)  @return array
    static public function filelist($path = '.')
    {
        if(!self::_init()) return array();
        if(!$path) return array();
        return ftp_nlist(self::$_conn_id, $path);
    }

    //关闭FTP @return boolean
    static public function close()
    {
        if(!self::$_conn_id) return false;
        return @ftp_close(self::$_conn_id);
    }

    //是否开启debug
    static public function debug()
    {
        self::$_debug = true;
    }

	static private function log($msg)
    {
        return error_log($msg, 3, 'ftp.log');
    }

}
