<?php
namespace Lib\Applications\Index\Models;
use Tang\Database\Sql\Model;

class Test extends Model
{
    protected $dbSource = 'pg';
    protected $tableName = 'user';
}