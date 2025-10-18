<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Customer",
    *     "name_underline"   =>"customer",
    *     "table_name"       =>"customer",
    *     "model_name"       =>"CustomerModel",
    *     "remark"           =>"客户信息记录",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-18 09:44:10",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CustomerModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CustomerModel extends Model{

	protected $name = 'customer';//客户信息记录

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
