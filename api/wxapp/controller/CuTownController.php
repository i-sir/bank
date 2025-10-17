<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"CuTown",
 *     "name_underline"          =>"cu_town",
 *     "controller_name"         =>"CuTown",
 *     "table_name"              =>"cu_town",
 *     "remark"                  =>"乡镇管理"
 *     "api_url"                 =>"/api/wxapp/cu_town/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-17 16:53:06",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\CuTownController();
 *     "test_environment"        =>"http://bank.ikun:9090/api/wxapp/cu_town/index",
 *     "official_environment"    =>"http://xcxkf213.aubye.com/api/wxapp/cu_town/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class CuTownController extends AuthController
{

    //public function initialize(){
    //	//乡镇管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/cu_town/index
     * http://xcxkf213.aubye.com/api/wxapp/cu_town/index
     */
    public function index()
    {
        $CuTownInit  = new \init\CuTownInit();//乡镇管理   (ps:InitController)
        $CuTownModel = new \initmodel\CuTownModel(); //乡镇管理   (ps:InitModel)

        $result = [];

        $this->success('乡镇管理-接口请求成功', $result);
    }


    /**
     * 乡镇管理 列表
     * @OA\Post(
     *     tags={"乡镇管理"},
     *     path="/wxapp/cu_town/find_town_list",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://bank.ikun:9090/api/wxapp/cu_town/find_town_list
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/cu_town/find_town_list
     *   api:  /wxapp/cu_town/find_town_list
     *   remark_name: 乡镇管理 列表
     *
     */
    public function find_town_list()
    {
        $CuTownInit  = new \init\CuTownInit();//乡镇管理   (ps:InitController)
        $CuTownModel = new \initmodel\CuTownModel(); //乡镇管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        $result                  = $CuTownInit->get_list($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }

    /**
     * 乡镇(村庄) 列表
     * @OA\Post(
     *     tags={"乡镇管理"},
     *     path="/wxapp/cu_town/find_town_plugin_list",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://bank.ikun:9090/api/wxapp/cu_town/find_town_plugin_list
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/cu_town/find_town_plugin_list
     *   api:  /wxapp/cu_town/find_town_plugin_list
     *   remark_name: 乡镇管理 列表
     *
     */
    public function find_town_plugin_list()
    {
        $CuTownInit  = new \init\CuTownInit();//乡镇管理   (ps:InitController)
        $CuTownModel = new \initmodel\CuTownModel(); //乡镇管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        $result                  = $CuTownInit->get_plugin_list($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


}
