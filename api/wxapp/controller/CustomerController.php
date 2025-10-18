<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Customer",
 *     "name_underline"          =>"customer",
 *     "controller_name"         =>"Customer",
 *     "table_name"              =>"customer",
 *     "remark"                  =>"客户信息记录"
 *     "api_url"                 =>"/api/wxapp/customer/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-18 09:44:10",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\CustomerController();
 *     "test_environment"        =>"http://bank.ikun:9090/api/wxapp/customer/index",
 *     "official_environment"    =>"http://xcxkf213.aubye.com/api/wxapp/customer/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class CustomerController extends AuthController
{

    //public function initialize(){
    //	//客户信息记录
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/customer/index
     * http://xcxkf213.aubye.com/api/wxapp/customer/index
     */
    public function index()
    {
        $CustomerInit  = new \init\CustomerInit();//客户信息记录   (ps:InitController)
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录   (ps:InitModel)

        $result = [];

        $this->success('客户信息记录-接口请求成功', $result);
    }


    /**
     * 客户信息记录 列表
     * @OA\Post(
     *     tags={"客户信息记录"},
     *     path="/wxapp/customer/find_customer_list",
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
     *   test_environment: http://bank.ikun:9090/api/wxapp/customer/find_customer_list
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/customer/find_customer_list
     *   api:  /wxapp/customer/find_customer_list
     *   remark_name: 客户信息记录 列表
     *
     */
    public function find_customer_list()
    {
        $CustomerInit  = new \init\CustomerInit();//客户信息记录   (ps:InitController)
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['user_id', '=', $this->user_id];
        if ($params["keyword"]) $where[] = ["username", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['type']) $where[] = ['type', '=', $params['type']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $CustomerInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $CustomerInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 客户信息记录 详情
     * @OA\Post(
     *     tags={"客户信息记录"},
     *     path="/wxapp/customer/find_customer",
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
     *   test_environment: http://bank.ikun:9090/api/wxapp/customer/find_customer
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/customer/find_customer
     *   api:  /wxapp/customer/find_customer
     *   remark_name: 客户信息记录 详情
     *
     */
    public function find_customer()
    {
        $CustomerInit  = new \init\CustomerInit();//客户信息记录    (ps:InitController)
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $CustomerInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 客户信息记录 添加
     * @OA\Post(
     *     tags={"客户信息记录"},
     *     path="/wxapp/customer/add_customer",
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
     *
     *    @OA\Parameter(
     *         name="town_id",
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
     *    @OA\Parameter(
     *         name="village_id",
     *         in="query",
     *         description="村庄id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="姓名",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="id_number",
     *         in="query",
     *         description="编号",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="information_info",
     *         in="query",
     *         description="业务信息",
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
     *         name="store_bank_info",
     *         in="query",
     *         description="存款银行信息",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="loan_bank_info",
     *         in="query",
     *         description="贷款银行信息",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="store_bank_ids",
     *         in="query",
     *         description="存款银行",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="store_amount",
     *         in="query",
     *         description="存款金额",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="this_store_amount",
     *         in="query",
     *         description="本行存款总额",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="loan_bank_ids",
     *         in="query",
     *         description="贷款银行",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="loan_amount",
     *         in="query",
     *         description="贷款金额",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="is_credit",
     *         in="query",
     *         description="本行信用客户:1是,2否",
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
     *   test_environment: http://bank.ikun:9090/api/wxapp/customer/add_customer
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/customer/add_customer
     *   api:  /wxapp/customer/add_customer
     *   remark_name: 客户信息记录 添加
     *
     */
    public function add_customer()
    {
        $CustomerInit  = new \init\CustomerInit();//客户信息记录    (ps:InitController)
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录   (ps:InitModel)
        $CuLevelModel  = new \initmodel\CuLevelModel(); //客户层级   (ps:InitModel)


        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;


        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];

        //存款银行信息
        $store_bank_list             = $params['store_bank_info']['bank_list'];
        $params['bank_proportion']   = $params['store_bank_info']['bank_proportion'] ?? 0;
        $params['store_amount']      = $params['store_bank_info']['store_amount'] ?? 0;
        $params['this_store_amount'] = $params['store_bank_info']['this_store_amount'] ?? 0;

        //贷款银行信息
        $loan_bank_list = $params['loan_bank_info']['bank_list'];

        //基础信息
        $information_info = $params['information_info'];


        //本行存款总额
        $this_store_amount = $params['store_bank_info']['this_store_amount'] ?? 0;

        //算出客户分层&星级
        $level_info = $CuLevelModel->where('min', '<=', $this_store_amount)
            ->where('max', '>=', $this_store_amount)
            ->order('max desc')
            ->find();

        $params['level']     = $level_info['level'] ?? 5;
        $params['star']      = $level_info['star'] ?? 1;
        $params['is_credit'] = 2;//默认为非信用客户
        // 客户归属:1本行客户,2他行客户,3交叉客户,4空白客户
        if (!empty($store_bank_list) && !empty($loan_bank_list)) {
            // 步骤1: 提取所有银行的is_level值
            // 从存款银行信息中提取is_level字段的值
            $store_levels = array_column($store_bank_list, 'is_level');
            // 从贷款银行信息中提取is_level字段的值
            $loan_levels = array_column($loan_bank_list, 'is_level');
            // 合并存款和贷款银行的所有is_level值
            $all_levels = array_merge($store_levels, $loan_levels);

            // 步骤2: 检查是否存在is_level为1或2的记录
            // 检查是否存在本行记录(is_level == 1)
            $has_level1 = in_array('1', $all_levels);
            // 检查是否存在他行记录(is_level == 2)
            $has_level2 = in_array('2', $all_levels);

            // 步骤3: 根据条件判断客户类型
            if ($has_level1 && !$has_level2) {
                // 情况1: 只有本行记录，没有他行记录 → 本行客户
                $params['ascription'] = 1;
            } elseif (!$has_level1 && $has_level2) {
                // 情况2: 只有他行记录，没有本行记录 → 他行客户
                $params['ascription'] = 2;
            } elseif ($has_level1 && $has_level2) {
                // 情况3: 同时存在本行和他行记录 → 交叉客户
                $params['ascription'] = 3;
            } else {
                // 情况4: 没有找到任何is_level为1或2的记录 → 空白客户
                $params['ascription'] = 4;
            }

            // 新增步骤: 检查是否存在is_credit为1的记录
            // 从贷款银行信息中提取is_credit字段的值
            $loan_credits = array_column($loan_bank_list, 'is_credit');
            // 检查是否存在任意一个is_credit为1的记录
            $has_credit = in_array('1', $loan_credits);
            // 设置is_credit参数
            $params['is_credit'] = $has_credit ? 1 : 0;
        } else {
            // 情况5: 存款或贷款银行信息为空 → 空白客户
            $params['ascription'] = 4;
            // 银行信息为空时，默认is_credit为2
            $params['is_credit'] = 2;
        }


        /** 提交更新 **/
        $result = $CustomerInit->api_edit_post($params, $where);
        if (empty($result)) $this->error("失败请重试");


        $this->success('提交成功', $result);
    }


}
