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
     * 基本信息配置 详情
     * @OA\Post(
     *     tags={"基本信息配置"},
     *     path="/wxapp/cu_field/find_field",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://bank.ikun:9090/api/wxapp/cu_field/find_field
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/cu_field/find_field
     *   api:  /wxapp/cu_field/find_field
     *   remark_name: 基本信息配置 详情
     *
     */
    public function find_field()
    {
        $CuFieldInit  = new \init\CuFieldInit();//基本信息配置    (ps:InitController)
        $CuFieldModel = new \initmodel\CuFieldModel(); //基本信息配置   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["is_show", "=", 1];
        $where[] = ["type", "=", $params["type"] ?? 1];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $CuFieldInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
