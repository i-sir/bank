<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"ShopLog",
    *     "name_underline"   =>"shop_log",
    *     "table_name"       =>"shop_log",
    *     "model_name"       =>"ShopLogModel",
    *     "remark"           =>"回访记录",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-16 15:54:24",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\ShopLogModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ShopLogModel extends Model{

	protected $name = 'shop_log';//回访记录

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
