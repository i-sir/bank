<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"CuVillage",
 *     "name_underline"          =>"cu_village",
 *     "controller_name"         =>"CuVillage",
 *     "table_name"              =>"cu_village",
 *     "remark"                  =>"村庄管理"
 *     "api_url"                 =>"/api/wxapp/cu_village/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-17 16:53:23",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\CuVillageController();
 *     "test_environment"        =>"http://bank.ikun:9090/api/wxapp/cu_village/index",
 *     "official_environment"    =>"http://xcxkf213.aubye.com/api/wxapp/cu_village/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class CuVillageController extends AuthController
{

    //public function initialize(){
    //	//村庄管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/cu_village/index
     * http://xcxkf213.aubye.com/api/wxapp/cu_village/index
     */
    public function index()
    {
        $CuVillageInit  = new \init\CuVillageInit();//村庄管理   (ps:InitController)
        $CuVillageModel = new \initmodel\CuVillageModel(); //村庄管理   (ps:InitModel)

        $result = [];

        $this->success('村庄管理-接口请求成功', $result);
    }


    /**
     * 村庄管理 列表
     * @OA\Post(
     *     tags={"村庄管理"},
     *     path="/wxapp/cu_village/find_village_list",
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
     *    @OA\Parameter(
     *         name="pid",
     *         in="query",
     *         description="乡镇id",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://bank.ikun:9090/api/wxapp/cu_village/find_village_list
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/cu_village/find_village_list
     *   api:  /wxapp/cu_village/find_village_list
     *   remark_name: 村庄管理 列表
     *
     */
    public function find_village_list()
    {
        $CuVillageInit  = new \init\CuVillageInit();//村庄管理   (ps:InitController)
        $CuVillageModel = new \initmodel\CuVillageModel(); //村庄管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["name", "like", "%{$params['keyword']}%"];
        if ($params["pid"]) $where[] = ["pid", "=", $params["pid"]];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        $result = $CuVillageInit->get_list($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


}
