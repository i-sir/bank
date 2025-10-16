<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"ShopLog",
    *     "name_underline"   =>"shop_log",
    *     "table_name"       =>"shop_log",
    *     "validate_name"    =>"ShopLogValidate",
    *     "remark"           =>"回访记录",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-16 15:54:24",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, ShopLog);
    * )
    */

class ShopLogValidate extends Validate
{

protected $rule = ['shop_name'=>'require',
];




protected $message = ['shop_name.require'=>'店铺名称不能为空!',
];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
