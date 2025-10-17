<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"CuVillage",
    *     "name_underline"   =>"cu_village",
    *     "table_name"       =>"cu_village",
    *     "validate_name"    =>"CuVillageValidate",
    *     "remark"           =>"村庄管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-17 16:53:23",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, CuVillage);
    * )
    */

class CuVillageValidate extends Validate
{

protected $rule = ['name'=>'require',
];




protected $message = ['name.require'=>'名称不能为空!',
];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
