<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CuSubbranch",
    *     "name_underline"   =>"cu_subbranch",
    *     "table_name"       =>"cu_subbranch",
    *     "model_name"       =>"CuSubbranchModel",
    *     "remark"           =>"支行管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-17 09:50:51",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CuSubbranchModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CuSubbranchModel extends Model{

	protected $name = 'cu_subbranch';//支行管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
