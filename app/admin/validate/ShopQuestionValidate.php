<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"ShopQuestion",
    *     "name_underline"   =>"shop_question",
    *     "table_name"       =>"shop_question",
    *     "validate_name"    =>"ShopQuestionValidate",
    *     "remark"           =>"回访配置",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-16 11:25:36",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, ShopQuestion);
    * )
    */

class ShopQuestionValidate extends Validate
{

protected $rule = ['name'=>'require',
];




protected $message = ['name.require'=>'题目名称不能为空!',
];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
