<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CuField",
    *     "name_underline"   =>"cu_field",
    *     "table_name"       =>"cu_field",
    *     "model_name"       =>"CuFieldModel",
    *     "remark"           =>"基本信息配置",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-16 17:39:43",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CuFieldModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CuFieldModel extends Model{

	protected $name = 'cu_field';//基本信息配置

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
