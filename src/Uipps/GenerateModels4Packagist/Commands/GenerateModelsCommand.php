<?php

namespace Uipps\GenerateModels4Packagist\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;

class GenerateModelsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:models
                            {--c|connection= : The name of the connection}
                            {--s|schema= : The name of the MySQL database}
                            {--t|table= : The name of the table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make models or controllers by Laravel self-function';

    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Create a new command instance.
     *
     * @param \Uipps\Coders\Model\Factory $models
     * @param \Illuminate\Contracts\Config\Repository $config
     */
    public function __construct(Repository $config)
    {
        parent::__construct();

        $this->config = $config;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = $this->getConnection();
        $schema = $this->getSchema($connection);
        $table = $this->getTable();

        // 支持dsn形式的连接形式；
        echo ' $connection: ' ; print_r($connection); echo "\r\n";
        echo ' $schema: ' ; print_r($schema); echo "\r\n";
        echo ' $table: ' ; var_dump($table); echo "\r\n";
        return ;
    }

    /**
     * @return string
     */
    protected function getConnection()
    {
        return $this->option('connection') ?: $this->config->get('database.default');
    }

    /**
     * @param $connection
     *
     * @return string
     */
    protected function getSchema($connection)
    {
        return $this->option('schema') ?: $this->config->get("database.connections.$connection.database");
    }

    /**
     * @return string
     */
    protected function getTable()
    {
        return $this->option('table');
    }
}
