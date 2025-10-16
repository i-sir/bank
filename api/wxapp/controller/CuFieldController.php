<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"CuField",
 *     "name_underline"          =>"cu_field",
 *     "controller_name"         =>"CuField",
 *     "table_name"              =>"cu_field",
 *     "remark"                  =>"基本信息配置"
 *     "api_url"                 =>"/api/wxapp/cu_field/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-16 17:39:43",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\CuFieldController();
 *     "test_environment"        =>"http://bank.ikun:9090/api/wxapp/cu_field/index",
 *     "official_environment"    =>"http://xcxkf213.aubye.com/api/wxapp/cu_field/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class CuFieldController extends AuthController
{

    //public function initialize(){
    //	//基本信息配置
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/cu_field/index
     * http://xcxkf213.aubye.com/api/wxapp/cu_field/index
     */
    public function index()
    {
        $CuFieldInit  = new \init\CuFieldInit();//基本信息配置   (ps:InitController)
        $CuFieldModel = new \initmodel\CuFieldModel(); //基本信息配置   (ps:InitModel)

        $result = [];

        $this->success('基本信息配置-接口请求成功', $result);
    }


    /**
     * 基本信息配置 列表
     * @OA\Post(
     *     tags={"基本信息配置"},
     *     path="/wxapp/cu_field/find_cu_field_list",
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
     *         description="客户类型:1农户,2牧户,3个体工商户,4公职人员,5政府全资或控股企业正式职工,6社区居民,7民营小微企业,8合作经济组织,9政府全资或控股企业,10行政事业单位,11其他群体",
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
     *   test_environment: http://bank.ikun:9090/api/wxapp/cu_field/find_cu_field_list
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/cu_field/find_cu_field_list
     *   api:  /wxapp/cu_field/find_cu_field_list
     *   remark_name: 基本信息配置 列表
     *
     */
    public function find_cu_field_list()
    {
        $CuFieldInit  = new \init\CuFieldInit();//基本信息配置   (ps:InitController)
        $CuFieldModel = new \initmodel\CuFieldModel(); //基本信息配置   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];

        if ($params["keyword"]) $where[] = ["name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['type']) $where[] = ['type', '=', $params['type']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $CuFieldInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $CuFieldInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 基本信息配置 详情
     * @OA\Post(
     *     tags={"基本信息配置"},
     *     path="/wxapp/cu_field/find_cu_field",
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
     *   test_environment: http://bank.ikun:9090/api/wxapp/cu_field/find_cu_field
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/cu_field/find_cu_field
     *   api:  /wxapp/cu_field/find_cu_field
     *   remark_name: 基本信息配置 详情
     *
     */
    public function find_cu_field()
    {
        $CuFieldInit  = new \init\CuFieldInit();//基本信息配置    (ps:InitController)
        $CuFieldModel = new \initmodel\CuFieldModel(); //基本信息配置   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $CuFieldInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 基本信息配置 编辑&添加
     * @OA\Post(
     *     tags={"基本信息配置"},
     *     path="/wxapp/cu_field/edit_cu_field",
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
     *         description="客户类型:1农户,2牧户,3个体工商户,4公职人员,5政府全资或控股企业正式职工,6社区居民,7民营小微企业,8合作经济组织,9政府全资或控股企业,10行政事业单位,11其他群体",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="question_type",
     *         in="query",
     *         description="类型:text=填写,radio=单选,checkbox=多选",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="备注",
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
     *         description="选择内容",
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
     *   test_environment: http://bank.ikun:9090/api/wxapp/cu_field/edit_cu_field
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/cu_field/edit_cu_field
     *   api:  /wxapp/cu_field/edit_cu_field
     *   remark_name: 基本信息配置 编辑&添加
     *
     */
    public function edit_cu_field()
    {
        $CuFieldInit  = new \init\CuFieldInit();//基本信息配置    (ps:InitController)
        $CuFieldModel = new \initmodel\CuFieldModel(); //基本信息配置   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 检测参数信息 **/
        $validateResult = $this->validate($params, 'CuField');
        if ($validateResult !== true) $this->error($validateResult);

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 提交更新 **/
        $result = $CuFieldInit->api_edit_post($params, $where);
        if (empty($result)) $this->error("失败请重试");


        if (empty($params["id"])) $msg = "添加成功";
        if (!empty($params["id"])) $msg = "编辑成功";
        $this->success($msg);
    }


    /**
     * 基本信息配置 删除
     * @OA\Post(
     *     tags={"基本信息配置"},
     *     path="/wxapp/cu_field/delete_cu_field",
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
     *   test_environment: http://bank.ikun:9090/api/wxapp/cu_field/delete_cu_field
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/cu_field/delete_cu_field
     *   api:  /wxapp/cu_field/delete_cu_field
     *   remark_name: 基本信息配置 删除
     *
     */
    public function delete_cu_field()
    {
        $CuFieldInit  = new \init\CuFieldInit();//基本信息配置    (ps:InitController)
        $CuFieldModel = new \initmodel\CuFieldModel(); //基本信息配置   (ps:InitModel)

        /** 获取参数 **/
        $params = $this->request->param();

        /** 删除数据 **/
        $result = $CuFieldInit->delete_post($params["id"]);
        if (empty($result)) $this->error("失败请重试");

        $this->success("删除成功");
    }


}
