<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CuVillage",
    *     "name_underline"   =>"cu_village",
    *     "table_name"       =>"cu_village",
    *     "model_name"       =>"CuVillageModel",
    *     "remark"           =>"村庄管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-17 16:53:23",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CuVillageModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CuVillageModel extends Model{

	protected $name = 'cu_village';//村庄管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
