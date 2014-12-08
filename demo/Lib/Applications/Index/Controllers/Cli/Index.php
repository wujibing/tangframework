<?php
namespace Lib\Applications\Index\Controllers\Cli;
use Lib\Applications\Index\Models\User;
use Tang\Cli\CliController;
use Tang\Database\Sql\Connections\Connection;
use Tang\Database\Sql\DB;
use Tang\Database\Sql\Schema\DDL;

class Index extends CliController
{
	public function hi()
	{
		$this->request->getResponse()->writeLine('Hi.你们好？');
	}
    public function main()
    {
        /**
        $builder = DB::get('pg')->getSchemaBuilder();
        //获取pg配置的schema构件
        $userDDL = $builder->createDDL('user',function(DDL $ddl)
        {
            $ddl->increments('userId');
            $ddl->string('name',20);
            $ddl->string('password',32);
            $ddl->string('nickName',40);
            $ddl->dateTime('registerTime');
            $ddl->bigInteger('registerIp',false,true);
            $ddl->create();
        });
        $builder->buildDDL($userDDL);
        $tagDDL = $builder->createDDL('tag',function(DDL $ddl)
        {
            $ddl->integer('tagId',true,true);
            $ddl->char('tag',255);
            $ddl->create();
        });
        $builder->buildDDL($tagDDL);
        $infoDDL = $builder->createDDL('user_info',function(DDL $ddl)
        {
            $ddl->integer('tagId',false,true);
            $ddl->string('email',100);
            $ddl->string('qq',100);
            $ddl->create();
            $ddl->primary('tagId');
        });
        $builder->buildDDL($infoDDL);
        $userTagDDL = $builder->createDDL('user_tag',function(DDL $ddl)
        {
            $ddl->integer('userId',false,true);
            $ddl->integer('tagId',false,true);
            $ddl->create();
            $ddl->primary(array('tagId','userId'));
        });
        $builder->buildDDL($userTagDDL);
        $userSignDDL = $builder->createDDL('user_sign',function(DDL $ddl)
        {
            $ddl->integer('date',false,true);
            $ddl->integer('userId',false,true);
            $ddl->integer('time',false,true);
            $ddl->create();
            $ddl->primary(array('date','userId'));
        });
        $builder->buildDDL($userSignDDL);
        **/
      //$user = User::find(83323);
      //$user->withDml('tags')->delete(

     // );//删除成功has one has many ,,,,,,,many to many
        $user = new User(null,'pg');
      $user->withDml('tags','sign','info')->insert(
          array(
              'name' => time(),
              'password' => 'password',
              'nickName' => 'nickName',
              'registerTime' => date('Y-m-d H:i:s'),
              'registerIp' => 1270001001,
              'tags' => array(
                  array('tag' => '和ihi2'),
                  array('tag' => '我们的')
              ),
              'sign' => array(
                  array('date' => date('Ymd'),'time' => 2),
                  array('date' => date('Ymd',strtotime('-1 day')),'time' => 2),
                  array('date' => date('Ymd',strtotime('-2 day')),'time' => 2),
                  array('date' => date('Ymd',strtotime('-3 day')),'time' => 2),
                  array('date' => date('Ymd',strtotime('-4 day')),'time' => 2),
              ),
              'info' => array('email'=>'11','qq'=>'22')
          )
      );
    }
    public function mysqlInsert()
    {
        $this->main();
    }
    public function pgsqlInsert()
    {
        $this->main('pg');
    }
    public function mysqlQuery($db='')
    {
        $builder = DB::get($db)->table('user');
        $beginTime = time();
        $builder->offset(800000,1000)->get();
        $this->request->getResponse()->writeLine('用时'.(time()-$beginTime).'秒');
    }
    public function pgsqlQuery()
    {
        $this->mysqlQuery('pg');
    }
    public function createUser(Connection $conn)
    {
        $conn = DB::get('pg');
        $userDDL = new DDL('user',function(DDL $ddl)
        {
            $ddl->create();
            $ddl->increments('uid');
            $ddl->string('name',32);
            $ddl->char('password',32);
            $ddl->char('nickName',40);
            $ddl->dateTime('registerTime');
            $ddl->integer('registerIp');
        });
        $userDDL->build($conn);
    }
}