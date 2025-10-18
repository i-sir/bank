<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"ShopLog",
 *     "name_underline"          =>"shop_log",
 *     "controller_name"         =>"ShopLog",
 *     "table_name"              =>"shop_log",
 *     "remark"                  =>"回访记录"
 *     "api_url"                 =>"/api/wxapp/shop_log/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-16 15:54:24",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ShopLogController();
 *     "test_environment"        =>"http://bank.ikun:9090/api/wxapp/shop_log/index",
 *     "official_environment"    =>"http://xcxkf213.aubye.com/api/wxapp/shop_log/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ShopLogController extends AuthController
{

    //public function initialize(){
    //	//回访记录
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/shop_log/index
     * http://xcxkf213.aubye.com/api/wxapp/shop_log/index
     */
    public function index()
    {
        $ShopLogInit  = new \init\ShopLogInit();//回访记录   (ps:InitController)
        $ShopLogModel = new \initmodel\ShopLogModel(); //回访记录   (ps:InitModel)

        $result = [];

        $this->success('回访记录-接口请求成功', $result);
    }


    /**
     * 回访记录 列表
     * @OA\Post(
     *     tags={"回访记录"},
     *     path="/wxapp/shop_log/find_log_list",
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="openid",
     *         in="query",
     *         description="openid",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="类型:1POS特约商户回访记录表,2刚察农商银行惠农金融服务点回访记录表,3聚合支付商户回访记录表",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="(选填)关键字搜索",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="is_paginate",
     *         in="query",
     *         description="false=分页(不传默认分页),true=不分页",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://bank.ikun:9090/api/wxapp/shop_log/find_log_list
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/shop_log/find_log_list
     *   api:  /wxapp/shop_log/find_log_list
     *   remark_name: 回访记录 列表
     *
     */
    public function find_log_list()
    {
        $ShopLogInit  = new \init\ShopLogInit();//回访记录   (ps:InitController)
        $ShopLogModel = new \initmodel\ShopLogModel(); //回访记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ["user_id", "=", $this->user_id];
        if ($params["keyword"]) $where[] = ["shop_name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['type']) $where[] = ['type', '=', $params['type']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $ShopLogInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $ShopLogInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 回访记录 详情
     * @OA\Post(
     *     tags={"回访记录"},
     *     path="/wxapp/shop_log/find_log",
     *
     *
     *
     *    @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://bank.ikun:9090/api/wxapp/shop_log/find_log
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/shop_log/find_log
     *   api:  /wxapp/shop_log/find_log
     *   remark_name: 回访记录 详情
     *
     */
    public function find_log()
    {
        $ShopLogInit  = new \init\ShopLogInit();//回访记录    (ps:InitController)
        $ShopLogModel = new \initmodel\ShopLogModel(); //回访记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ShopLogInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 回访记录 添加
     * @OA\Post(
     *     tags={"回访记录"},
     *     path="/wxapp/shop_log/add_log",
     *
     *
     *
     *    @OA\Parameter(
     *         name="openid",
     *         in="query",
     *         description="openid",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="类型:1POS特约商户回访记录表,2刚察农商银行惠农金融服务点回访记录表,3聚合支付商户回访记录表",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="shop_name",
     *         in="query",
     *         description="店铺名称",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="value",
     *         in="query",
     *         description="选项",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id空添加,存在编辑",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://bank.ikun:9090/api/wxapp/shop_log/add_log
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/shop_log/add_log
     *   api:  /wxapp/shop_log/add_log
     *   remark_name: 回访记录 添加
     *
     */
    public function add_log()
    {
        $ShopLogInit  = new \init\ShopLogInit();//回访记录    (ps:InitController)
        $ShopLogModel = new \initmodel\ShopLogModel(); //回访记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 检测参数信息 **/
        $validateResult = $this->validate($params, 'ShopLog');
        if ($validateResult !== true) $this->error($validateResult);

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 提交更新 **/
        $result = $ShopLogInit->api_edit_post($params, $where);
        if (empty($result)) $this->error("失败请重试");


        if (empty($params["id"])) $msg = "添加成功";
        if (!empty($params["id"])) $msg = "编辑成功";
        $this->success($msg);
    }


}
