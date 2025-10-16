<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"ShopQuestion",
 *     "name_underline"          =>"shop_question",
 *     "controller_name"         =>"ShopQuestion",
 *     "table_name"              =>"shop_question",
 *     "remark"                  =>"回访配置"
 *     "api_url"                 =>"/api/wxapp/shop_question/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-16 11:25:36",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ShopQuestionController();
 *     "test_environment"        =>"http://bank.ikun:9090/api/wxapp/shop_question/index",
 *     "official_environment"    =>"http://xcxkf213.aubye.com/api/wxapp/shop_question/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ShopQuestionController extends AuthController
{

    //public function initialize(){
    //	//回访配置
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/shop_question/index
     * http://xcxkf213.aubye.com/api/wxapp/shop_question/index
     */
    public function index()
    {
        $ShopQuestionInit  = new \init\ShopQuestionInit();//回访配置   (ps:InitController)
        $ShopQuestionModel = new \initmodel\ShopQuestionModel(); //回访配置   (ps:InitModel)

        $result = [];

        $this->success('回访配置-接口请求成功', $result);
    }


    /**
     * 题库 列表
     * @OA\Post(
     *     tags={"回访配置"},
     *     path="/wxapp/shop_question/find_question_list",
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
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://bank.ikun:9090/api/wxapp/shop_question/find_question_list
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/shop_question/find_question_list
     *   api:  /wxapp/shop_question/find_question_list
     *   remark_name: 回访配置 列表
     *
     */
    public function find_question_list()
    {
        $ShopQuestionInit  = new \init\ShopQuestionInit();//回访配置   (ps:InitController)
        $ShopQuestionModel = new \initmodel\ShopQuestionModel(); //回访配置   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params['type']) $where[] = ['type', '=', $params['type']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        $result                  = $ShopQuestionInit->get_list($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


}
