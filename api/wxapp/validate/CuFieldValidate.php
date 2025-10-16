<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"CuField",
    *     "name_underline"   =>"cu_field",
    *     "table_name"       =>"cu_field",
    *     "validate_name"    =>"CuFieldValidate",
    *     "remark"           =>"基本信息配置",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-16 17:39:43",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, CuField);
    * )
    */

class CuFieldValidate extends Validate
{

protected $rule = ['name'=>'require',
];




protected $message = ['name.require'=>'备注不能为空!',
];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
