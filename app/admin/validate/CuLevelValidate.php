<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"CuLevel",
    *     "name_underline"   =>"cu_level",
    *     "table_name"       =>"cu_level",
    *     "validate_name"    =>"CuLevelValidate",
    *     "remark"           =>"客户层级",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-18 10:13:37",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, CuLevel);
    * )
    */

class CuLevelValidate extends Validate
{

protected $rule = [];




protected $message = [];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
