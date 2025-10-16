<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"ShopQuestion",
    *     "name_underline"   =>"shop_question",
    *     "table_name"       =>"shop_question",
    *     "model_name"       =>"ShopQuestionModel",
    *     "remark"           =>"回访配置",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-16 11:25:36",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\ShopQuestionModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ShopQuestionModel extends Model{

	protected $name = 'shop_question';//回访配置

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
