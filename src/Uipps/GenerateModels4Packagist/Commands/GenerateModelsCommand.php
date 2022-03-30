<?php
/*
 自动生成model、controller等，支持指定目录

 如：
 php artisan generate:models -c "mysql://root:101010@127.0.0.1:3511/laravel_dev" -d laravel_dev

 -- 指定目录 -p
 php artisan generate:models -c "mysql://root:101010@127.0.0.1:3511/laravel_dev" -p Uipps/


 */
namespace Uipps\GenerateModels4Packagist\Commands;

use Illuminate\Console\Command;
use Illuminate\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Exception;

class GenerateModelsCommand extends Command
{
    const DOT_REPLACE_TO_STR = '---______---'; // 配置中不能有.点符号, 否则会被当成数组的层级，因此需要将配置中的.替换成特殊符号便于还原

    /*
    The name and signature of the console command.
 不要跟如下冲突：
 -h, --help                     Display help for the given command. When no command is given display help for the list command
 -q, --quiet                    Do not output any message
 -V, --version                  Display this application version
     --ansi|--no-ansi           Force (or disable --no-ansi) ANSI output
 -n, --no-interaction           Do not ask any interactive question
     --env[=ENV]                The environment the command should run under
 -v|vv|vvv, --verbose           Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

     */
    protected $signature = 'generate:models
                            {--c|connection= : The name of the connection}
                            {--d|database= : The name of the MySQL database}
                            {--t|table= : The name of the table}
                            {--p|path_relative= : The relative path}
                            ';

    protected $description = 'Make models or controllers by Laravel self-function';

    protected $_connection = '';    // 数据库连接的名称，连接别名
    protected $_database = '';      // 指定的数据库名称
    protected $_table = '';         // 指定的数据表名称

    // Execute the console command.
    public function handle()
    {

        $this->info(date('Y-m-d H:i:s') . ' generate model begin: ');

        $this->doGenerateModels();

        $this->info(date('Y-m-d H:i:s') . ' generate model end!');
        return ;
    }

    protected function doGenerateModels() {
        $this->_connection = $this->getConnection();
        $this->_database = $this->getSchema();
        $this->_table_list = $this->getTables($this->_connection);
        $relative_path = $this->option('path_relative');            // 相对路径

        // 支持dsn形式的连接形式；
        echo ' $connection: ' ; print_r($this->_connection); echo "\r\n";
        echo ' $database: ' ; print_r($this->_database); echo "\r\n";
        echo ' $tables: ' ; print_r($this->_table_list); echo "\r\n";

        // 设置好数据库连接信息后，开始执行自动生成Model、Controller
        if (!$this->_table_list)
            return ;

        foreach ($this->_table_list as $l_tbl) {
            self::generateOneTable($relative_path, $l_tbl);
        }
        return ;
    }

    /*  生成单个model
        由于 php artisan make:model Uipps/Admin/Project --controller=uipps/Admin/Project 会报错 "--controller" option does not accept a value.
        再者 php artisan make:model uipps/Admin/Project --controller'  -- 生成的 controller路径不是期望的uipps/Admin/路径
        而  php artisan make:controller 能同时指定model和control的路径，所以make:controller才是正确选择

        // 相当于执行 php artisan make:controller uipps/Admin/ProjectController --model=uipps/Admin/Project
        //Artisan::call('make:controller', ['uipps/Admin2/ProjectController', '--resource', '--model=uipps/Admin2/Project'], $outputBuffer);
        //$eCode = Artisan::call('make:controller uipps/Admin/ProjectController --model=uipps/Admin/Project --quiet');
        //$eCode = Artisan::call('make:controller uipps/Admin/ProjectController --model=uipps/Admin/Project');
     */
    protected function generateOneTable($a_path, $a_table) {
        $fmt_table = self::classify($a_table);    // 下划线等变驼峰命名
        $fmt_path = self::fmtPath($a_path);

        //$cmd = 'make:controller uipps/Admin/ProjectController --model=uipps/Admin/Project --quiet';
        $cmd = 'make:controller '. $fmt_path . $fmt_table .'Controller --model='. $fmt_path . $fmt_table .' --quiet';
        $exitCode = Artisan::call($cmd);
        $output = Artisan::output();
        echo '  make:controller, Table ' . $a_table . ' (' . $fmt_table . '), $exitCode: ' . var_export($exitCode, true) . ' $output: ' . var_export($output, true) . "\r\n";

        return ;
    }

    protected function getConnection() {
        $l_conn = $this->option('connection');  // 参数接收

        $db_schema_default = \Config::get('database.default');
        if (!$l_conn || $l_conn == $db_schema_default) {
            // 默认连接，空字符串或 Config::get('database.default'); 均可
            return $this->_connection;
        }

        // 当前config/database.php中的配置
        $db_config_list = \Config::get('database.connections');
        $db_default_config = \Config::get('database.connections.' . $db_schema_default);

        if (isset($db_config_list[$l_conn])) {
            $this->_connection = $l_conn;
        } else {
            // 解析一下，如果指定了host,port等信息
            $dsn = parse_url($l_conn); //print_r($dsn);
            //    [scheme] => mysql
            //    [host] => 127.0.0.1
            //    [port] => 3511
            //    [user] => root
            //    [pass] => 101010
            //    [path] => /laravel_dev
            if (isset($dsn['scheme']) && 'mysql' != strtolower($dsn['scheme'])) {
                // TODO 暂时只支持mysql，其他类型pgsql、sqlsrv有空再支持, 这里直接退出，应该采用异常抛出进行处理。
                //throw new \Exception('暂只支持mysql，其他类型尽请期待！');
                exit('暂只支持mysql，其他类型尽请期待！');
            }

            $db_connect_info = $db_default_config;
            if (isset($dsn['host']))
                $db_connect_info['host'] = $dsn['host'];
            if (isset($dsn['port']))
                $db_connect_info['port'] = $dsn['port'];
            if (isset($dsn['user']))
                $db_connect_info['username'] = $dsn['user'];
            if (isset($dsn['pass']))
                $db_connect_info['password'] = $dsn['pass'];

            $l_tmp_db = ''; // dsn中的数据库
            if (isset($dsn['path'])) {
                $l_tmp_db = basename($dsn['path']);
                if ($l_tmp_db) {
                    $db_connect_info['database'] = $l_tmp_db;   // $dsn['path']可能是/,basename后就是空字符串。
                    $this->_database = $l_tmp_db;               // 数据库指定，也可能是空的，可被-d参数覆盖
                }
            }
            if (!$l_tmp_db && $this->option('database') != $db_schema_default) {
                $db_connect_info['database'] = $this->option('database');
            }

            // 是否就是默认连接，默认连接也不需要修改 $this->_connection 的值
            if ($db_connect_info == $db_default_config) {
                $this->_database = '';  // 默认即可
            } else {
                // 新增一条数据库配置
                $this->_connection = self::getConnectName($db_connect_info);
                \Config::set('database.connections.' . $this->_connection, $db_connect_info);
                //print_r(\Config::get('database.connections'));
            }
        }

        return $this->_connection;
    }

    protected function getSchema() {
        $l_schema = $this->option('database');  // 参数接收
        if (!$l_schema) {
            // 未指定，可能是dsn中指定的，也可能是默认 Config::get("database.connections.$connection.database"
            return $this->_database;
        }

        // dsn中和参数-d不一致的时候，给出提示信息
        if ($this->_database && $this->_database != $l_schema) {
            //throw new Exception('-c (DSN) and -d are not equal!');
            exit('-c (DSN) and -d are not equal!');
        }

        // 用指定的赋值。$this->_database 为空或等于$l_schema，均可
        $this->_database = $l_schema;

        // 给该连接的database赋值，因为可能指定了非默认数据库名称
        $l_conn = $this->_connection;
        if (!$l_conn)
            $l_conn = \Config::get('database.default');

        $db_connect_info = \Config::get('database.connections.' . $l_conn);
        $db_connect_info['database'] = $this->_database;
        \Config::set('database.connections.' . $l_conn, $db_connect_info);

        // 检查一下指定的数据库是否存在，如果不存在，则提示。
        //$current_db_name = DB::connection()->getDoctrineSchemaManager()->getSchemaSearchPaths();var_dump($current_db_name[0]); // 当前数据库名称
        $db_list = DB::connection($l_conn)->getDoctrineSchemaManager()->listDatabases();//print_r($db_list);
        if (!in_array($this->_database, $db_list)) {
            throw new \Exception($this->_database . ' database not exist!');
        }

        return $this->_database;
    }

    // 可能是单个表，未指定则返回所有表.
    protected function getTables($a_conn) {
        $l_tbl = $this->option('table');
        //echo ' $table: ' ; var_dump($l_tbl); echo "\r\n";

        // 获取所有数据表：
        $tbl_list = DB::connection($a_conn)->getDoctrineSchemaManager()->listTableNames();//print_r($tbl_list);
        if (!$l_tbl)
            return $tbl_list;

        if (!in_array($l_tbl, $tbl_list))
            throw new \Exception($l_tbl . ' table not exist!');

        return [$l_tbl];
    }

    // 由于config::set的限制，必须保证返回的字符串中没有点符号'.'
    public static function getConnectName($p_arr, $replace_dot = true, $with_dbname = true) {
        if (!is_array($p_arr)) {
            throw new \Exception('Invalid array p_arr');
        }
        // dsn不需要携带db_name信息，因为可能并没有选择数据库
        if (!isset($p_arr['port']) || '' == $p_arr['port']) $p_arr['port'] = 3306; // 补充默认端口，统一格式
        if (array_key_exists('password', $p_arr)) {
            $dsn = "mysql://".$p_arr['username'].":".$p_arr['password']."@".$p_arr['host'].":".$p_arr['port']."/";
        } else {
            throw new \Exception('Invalid array p_arr');
        }
        if ($with_dbname) $dsn .= (isset($p_arr['database']) ? $p_arr['database'] : '');

        if ($replace_dot)
            return str_replace('.', self::DOT_REPLACE_TO_STR, $dsn); // 保证没有.符号, 防止config.set的时候出现问题
        return $dsn;
    }

    // hello world ==> HelloWorld
    public static function classify(string $word) {
        return str_replace([' ', '_', '-'], '', ucwords($word, ' _-'));
    }

    // 路径转成相对路径，[\uipps\admin\, uipps\admin, /uipps/admin/, uipps/admin] ...  ===> 都转成 uipps/admin/
    public static function fmtPath($a_str) {
        if (!$a_str) return '';
        $a_str = str_replace(['\\', '//'], '/', $a_str);
        //$a_str = str_replace('//', '/', $a_str);          //  上面一行代码就搞定了 \\uipps\\admin\\ 会被替换成单/.
        $a_str = trim($a_str, '/');
        if (!$a_str)
            return '';
        return $a_str . '/';
    }
}
