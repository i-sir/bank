<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CuTown",
    *     "name_underline"   =>"cu_town",
    *     "table_name"       =>"cu_town",
    *     "model_name"       =>"CuTownModel",
    *     "remark"           =>"乡镇管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-17 16:53:06",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CuTownModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CuTownModel extends Model{

	protected $name = 'cu_town';//乡镇管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
