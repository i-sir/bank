<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Customer",
    *     "name_underline"   =>"customer",
    *     "table_name"       =>"customer",
    *     "validate_name"    =>"CustomerValidate",
    *     "remark"           =>"客户信息记录",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-18 09:44:10",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Customer);
    * )
    */

class CustomerValidate extends Validate
{

protected $rule = [];




protected $message = [];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
