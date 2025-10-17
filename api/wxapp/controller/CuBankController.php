<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"CuBank",
 *     "name_underline"          =>"cu_bank",
 *     "controller_name"         =>"CuBank",
 *     "table_name"              =>"cu_bank",
 *     "remark"                  =>"银行管理"
 *     "api_url"                 =>"/api/wxapp/cu_bank/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-17 09:59:42",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\CuBankController();
 *     "test_environment"        =>"http://bank.ikun:9090/api/wxapp/cu_bank/index",
 *     "official_environment"    =>"http://xcxkf213.aubye.com/api/wxapp/cu_bank/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class CuBankController extends AuthController
{

    //public function initialize(){
    //	//银行管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/cu_bank/index
     * http://xcxkf213.aubye.com/api/wxapp/cu_bank/index
     */
    public function index()
    {
        $CuBankInit  = new \init\CuBankInit();//银行管理   (ps:InitController)
        $CuBankModel = new \initmodel\CuBankModel(); //银行管理   (ps:InitModel)

        $result = [];

        $this->success('银行管理-接口请求成功', $result);
    }


    /**
     * 银行管理 列表
     * @OA\Post(
     *     tags={"银行管理"},
     *     path="/wxapp/cu_bank/find_bank_list",
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
     *    @OA\Parameter(
     *         name="is_deposit",
     *         in="query",
     *         description="true 存款银行列表",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *    @OA\Parameter(
     *         name="is_loan",
     *         in="query",
     *         description="true 贷款银行列表",
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
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://bank.ikun:9090/api/wxapp/cu_bank/find_bank_list
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/cu_bank/find_bank_list
     *   api:  /wxapp/cu_bank/find_bank_list
     *   remark_name: 银行管理 列表
     *
     */
    public function find_bank_list()
    {
        $CuBankInit  = new \init\CuBankInit();//银行管理   (ps:InitController)
        $CuBankModel = new \initmodel\CuBankModel(); //银行管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params["is_deposit"]) $where[] = ["is_deposit", "=", $params["is_deposit"]];
        if ($params["is_loan"]) $where[] = ["is_loan", "=", $params["is_loan"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        $result                  = $CuBankInit->get_list($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


}
