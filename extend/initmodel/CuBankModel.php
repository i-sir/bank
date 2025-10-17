<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"CuBank",
    *     "name_underline"   =>"cu_bank",
    *     "table_name"       =>"cu_bank",
    *     "model_name"       =>"CuBankModel",
    *     "remark"           =>"银行管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-17 09:59:42",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\CuBankModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class CuBankModel extends Model{

	protected $name = 'cu_bank';//银行管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
