<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CuLevel",
    *     "name_underline"   =>"cu_level",
    *     "table_name"       =>"cu_level",
    *     "model_name"       =>"CuLevelModel",
    *     "remark"           =>"客户层级",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-18 10:13:37",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CuLevelModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CuLevelModel extends Model{

	protected $name = 'cu_level';//客户层级

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
