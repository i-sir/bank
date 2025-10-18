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
     *    @OA\Parameter(
     *         name="ascription",
     *         in="query",
     *         description="客户归属:1本行客户,2他行客户,3交叉客户,4空白客户",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="level",
     *         in="query",
     *         description="客户分层:1战略客户,2重点客户,3价值客户,4一般客户,5长尾客户",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *    @OA\Parameter(
     *         name="star",
     *         in="query",
     *         description="星级:1准星级,2二星级,3三星级,4四星级,5五星级,6六星级,7七星级,8私人银行级",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
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
        if ($params["keyword"]) $where[] = ["username|id_number|phone", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['type']) $where[] = ['type', '=', $params['type']];
        if ($params['ascription']) $where[] = ['ascription', '=', $params['ascription']];
        if ($params['level']) $where[] = ['level', '=', $params['level']];
        if ($params['star']) $where[] = ['star', '=', $params['star']];
        if ($params['town_id']) $where[] = ['town_id', '=', $params['town_id']];
        if ($params['village_id']) $where[] = ['village_id', '=', $params['village_id']];


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
     * 客户信息记录 添加&编辑
     * @OA\Post(
     *     tags={"客户信息记录"},
     *     path="/wxapp/customer/edit_customer",
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
     *   test_environment: http://bank.ikun:9090/api/wxapp/customer/edit_customer
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/customer/edit_customer
     *   api:  /wxapp/customer/edit_customer
     *   remark_name: 客户信息记录 添加
     *
     */
    public function edit_customer()
    {
        $this->checkAuth();
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
        if ($this_store_amount > 0) $this_store_amount = $this_store_amount * 10000;//万单位

        //算出客户分层&星级
        $level_info = $CuLevelModel->where('min', '<=', $this_store_amount)
            ->where('max', '>=', $this_store_amount)
            ->order('max desc')
            ->find();

        $params['level']          = $level_info['level'] ?? 5;
        $params['star']           = $level_info['star'] ?? 1;
        $params['is_credit']      = 2;//默认为非信用客户
        $params['store_bank_ids'] = [];//存款银行id 数组
        $params['loan_bank_ids']  = [];//贷款银行id 数组


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


        //提取存款银行id
        if ($store_bank_list) $params['store_bank_ids'] = array_column($store_bank_list, 'bank_id');


        //提取贷款银行id
        if ($loan_bank_list) $params['loan_bank_ids'] = array_column($loan_bank_list, 'bank_id');

        /** 提交更新 **/
        $result = $CustomerInit->api_edit_post($params, $where);
        if (empty($result)) $this->error("失败请重试");


        $this->success('提交成功', $result);
    }


    /**
     * 统计
     * @OA\Post(
     *     tags={"客户信息记录"},
     *     path="/wxapp/customer/find_statistics",
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
     *    @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="日期  1昨天,2本周,3本月,4本年",
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
     *   test_environment: http://bank.ikun:9090/api/wxapp/customer/find_statistics
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/customer/find_statistics
     *   api:  /wxapp/customer/find_statistics
     *   remark_name: 统计
     *
     */
    public function find_statistics()
    {
        $this->checkAuth();

        $CustomerModel = new \initmodel\CustomerModel(); // 客户信息记录模型
        $CuBankModel   = new \initmodel\CuBankModel(); //银行管理   (ps:InitModel)

        $params = $this->request->param();

        // 对比时间段（昨天/上周/上月/上年）
        $before_map   = [];
        $before_map[] = ['user_id', '=', $this->user_id];

        // 当前时间段（今天/本周/本月/本年）
        $map   = [];
        $map[] = ['user_id', '=', $this->user_id];

        /**
         * 设置时间范围条件
         * 日期参数说明：
         * 1: 昨天（对比前天）
         * 2: 本周（对比上周）
         * 3: 本月（对比上月）
         * 4: 本年（对比上年）
         */
        if ($params['date'] == 1) {
            // 昨天 - 对比时间段
            $before_start = strtotime('yesterday');
            $before_end   = strtotime('yesterday 23:59:59');
            $before_map[] = ['create_time', 'between', [$before_start, $before_end]];

            // 今天 - 当前时间段
            $current_start = strtotime('today');
            $current_end   = time();
            $map[]         = ['create_time', 'between', [$current_start, $current_end]];

        } elseif ($params['date'] == 2) {
            // 上周 - 对比时间段
            $before_start = strtotime('last week monday');
            $before_end   = strtotime('last week sunday 23:59:59');
            $before_map[] = ['create_time', 'between', [$before_start, $before_end]];

            // 本周 - 当前时间段
            $current_start = strtotime('this week monday');
            $current_end   = time();
            $map[]         = ['create_time', 'between', [$current_start, $current_end]];

        } elseif ($params['date'] == 3) {
            // 上月 - 对比时间段
            $before_start = strtotime('first day of last month');
            $before_end   = strtotime('last day of last month 23:59:59');
            $before_map[] = ['create_time', 'between', [$before_start, $before_end]];

            // 本月 - 当前时间段
            $current_start = strtotime('first day of this month');
            $current_end   = time();
            $map[]         = ['create_time', 'between', [$current_start, $current_end]];

        } elseif ($params['date'] == 4) {
            // 上年 - 对比时间段
            $before_start = strtotime('first day of january last year');
            $before_end   = strtotime('last day of december last year 23:59:59');
            $before_map[] = ['create_time', 'between', [$before_start, $before_end]];

            // 本年 - 当前时间段
            $current_start = strtotime('first day of january this year');
            $current_end   = time();
            $map[]         = ['create_time', 'between', [$current_start, $current_end]];
        }

        /**
         * 客户类型配置处理
         * 从系统配置获取客户类型，格式如：1=农户,2=商户,3=企业
         */
        $customer_type = $this->getParams(cmf_config('customer_type'), '/');
        $type_list     = [];
        foreach ($customer_type as $item) {
            list($key, $value) = explode('=', $item);
            $type_list[(int)$key] = $value;
        }

        /** 客户统计 */
        $customer_result = [];
        foreach ($type_list as $key => $value) {

            // 添加客户类型筛选条件
            $customer_map   = [];
            $customer_map[] = ['type', '=', $key];

            // 查询当前时间段和对比时间段的客户数量
            $current_count = $CustomerModel->where(array_merge($map, $customer_map))->count();
            $before_count  = $CustomerModel->where(array_merge($before_map, $customer_map))->count();

            /**
             * 计算增减变化
             * change_count: 变化的绝对值（用于显示变化量）
             * change_trend: 趋势标识（1=增长, -1=减少, 0=持平）
             * change_text: 显示的文本格式（带+/-符号）
             */
            $change_count = $current_count - $before_count;
            $trend        = 0; // 默认持平

            if ($change_count > 0) {
                $trend = 1; // 增长
            } elseif ($change_count < 0) {
                $trend = -1; // 减少
            }

            // 构建返回结果数组
            $customer_result[] = [
                'type'         => $key,           // 客户类型ID
                'name'         => $value,         // 客户类型名称
                'total'        => $current_count, // 当前时间段客户数量
                'before_total' => $before_count,  // 对比时间段客户数量
                'change_count' => abs($change_count), // 变化的绝对值
                'change_trend' => $trend,         // 趋势：1增长，-1减少，0持平
                'change_text'  => $trend > 0 ? '+' . $change_count : ($trend < 0 ? $change_count : '0') // 显示的文本格式
            ];
        }


        /** 客户归属 占比统计 圆饼图 **/

        $customer_attribution = $this->getParams(cmf_config('customer_attribution'), '/');
        $attribution_list     = [];
        foreach ($customer_attribution as $item) {
            list($key, $value) = explode('=', $item);
            $attribution_list[(int)$key] = $value;
        }


        $attribution_result = [];//返回结果集
        // 遍历所有客户归属类型，统计每个类型的客户数量
        foreach ($attribution_list as $key => $value) {
            // 添加客户归属筛选条件
            $attribution_map   = [];
            $attribution_map[] = ['ascription', '=', $key];

            // 查询当前时间段的客户数量
            $customer_number = $CustomerModel->where(array_merge($map, $attribution_map))->count();

            //返回结果集
            $attribution_result['series']['data'][] = [
                'name'  => $value,  // 归属类型名称
                'value' => $customer_number  // 客户数量
            ];
        }


        /** 贷款存款公共数据 **/
        //本行
        $bank_map   = [];
        $bank_map[] = ['is_level', '=', 1];
        $bank_map[] = ['is_show', '=', 1];
        $bank_info  = $CuBankModel->where($bank_map)->find();

        //其他银行
        $other_bank_map   = [];
        $other_bank_map[] = ['is_level', '=', 2];
        $other_bank_map[] = ['is_show', '=', 1];
        $other_bank_ids   = $CuBankModel->where($other_bank_map)->column('id');
        /** 贷款存款公共数据 **/


        /** 贷款人数统计 **/
        $loan_result = [];//返回结果集

        $this_bank_number  = 0;//本行
        $other_bank_number = 0;//其他 银行
        $loan_bank_number  = 0;//总人数

        $loan_list = $CustomerModel->where($map)->select();
        foreach ($loan_list as $loan_item) {
            if ($loan_item['loan_bank_ids']) {
                $loan_bank_ids = $this->getParams($loan_item['loan_bank_ids'], ',');

                if (in_array($bank_info['id'], $loan_bank_ids)) {
                    $this_bank_number++;
                }

                // 检查是否包含任何其他银行（使用array_intersect判断交集）
                if (!empty(array_intersect($other_bank_ids, $loan_bank_ids))) {
                    $other_bank_number++;
                }

                $loan_bank_number++;
            }
        }

        $loan_result = [
            'this_bank_number'  => $this_bank_number,
            'other_bank_number' => $other_bank_number,
            'total_bank_number' => $loan_bank_number
        ];


        /** 存款人数统计 **/
        $store_result      = [];//返回结果集
        $this_bank_number  = 0;//本行
        $other_bank_number = 0;//其他 银行
        $store_bank_number = 0;//总人数

        $store_list = $CustomerModel->where($map)->select();
        foreach ($store_list as $store_item) {
            if ($store_item['store_bank_ids']) {
                $store_bank_ids = $this->getParams($store_item['store_bank_ids'], ',');
                if (in_array($bank_info['id'], $store_bank_ids)) {
                    $this_bank_number++;
                }
                // 检查是否包含任何其他银行（使用array_intersect判断交集）
                if (!empty(array_intersect($other_bank_ids, $store_bank_ids))) {
                    $other_bank_number++;
                }

                $store_bank_number++;
            }
        }
        $store_result = [
            'this_bank_number'  => $this_bank_number,
            'other_bank_number' => $other_bank_number,
            'total_bank_number' => $store_bank_number
        ];


        /** 占比统计 **/
        // 获取贷款总人数和存款总人数
        $loan_total  = $loan_bank_number ?? 0; // 贷款总人数
        $store_total = $store_bank_number ?? 0; // 存款总人数
        $total       = ($loan_total + $store_total) ?? 0; // 总人数（贷款+存款）

        // 初始化占比（默认0，避免除零错误）
        $loan_ratio  = 0; // 贷款人数占比
        $store_ratio = 0; // 存款人数占比

        // 计算占比（保留2位小数，转换为百分比）
        if ($total > 0) {
            $loan_ratio  = number_format(($loan_total / $total) * 100, 2); // 贷款人数占比 = 贷款总人数 / 总人数 * 100%
            $store_ratio = number_format(($store_total / $total) * 100, 2); // 存款人数占比 = 存款总人数 / 总人数 * 100%
        }

        // 占比结果集
        $ratio_result = [
            'loan_ratio'   => $loan_ratio, // 贷款人数占比（带百分比符号）
            'store_ratio'  => $store_ratio, // 存款人数占比（带百分比符号）
            'total_people' => $total // 总人数（可选，用于参考）
        ];


        /** 信用用户统计 **/
        //贷款人数
        $loan_credit_number = $loan_bank_number;
        //信誉用户人数
        $credit_map    = [];
        $credit_map[]  = ['is_credit', '=', 1];
        $credit_number = $CustomerModel->where(array_merge($map, $credit_map))->count();


        //信用用户占比
        $credit_ratio  = number_format(($credit_number / $loan_bank_number) * 100, 2);
        $credit_result = [
            'credit_number'      => $credit_number, // 信用用户人数
            'loan_credit_number' => $loan_credit_number, // 贷款人数
            'credit_ratio'       => $credit_ratio, // 信用用户占比（带百分比符号）
        ];


        /** 地区统计 **/
        $CuTownInit = new \init\CuTownInit();//乡镇管理   (ps:InitController)
        $town_map   = [];
        $town_map[] = ['is_show', '=', 1];
        $town_list  = $CuTownInit->get_list();

        $town_result = [];

        //总数
        $total_number = $CustomerModel->where($map)->count();

        foreach ($town_list as $town_item) {
            $town_map100   = [];
            $town_map100[] = ['town_id', '=', $town_item['id']];
            $town_number   = $CustomerModel->where(array_merge($map, $town_map100))->count();

            // 计算百分比（处理总数为0的情况，避免除零错误）
            $percentage = 0;
            if ($total_number > 0) {
                // 计算百分比并保留2位小数
                $percentage = number_format(($town_number / $total_number) * 100, 2);
            }

            $town_result[] = [
                'name'       => $town_item['name'],
                'number'     => $town_number,
                'percentage' => $percentage  // 拼接百分号，直观显示
            ];
        }


        /** 客户等级统计 **/
        $customer_star = $this->getParams(cmf_config('customer_star'), '/');
        $star_list     = [];
        foreach ($customer_star as $item) {
            list($key, $value) = explode('=', $item);
            $star_list[(int)$key] = $value;
        }

        $star_result = [];//返回结果集
        foreach ($star_list as $key => $star_item) {
            $star_map      = [];
            $star_map[]    = ['star', '=', $key];
            $star_number   = $CustomerModel->where(array_merge($map, $star_map))->count();
            $star_result[] = [
                'name'       => $star_item,
                'number'     => $star_number,
                'percentage' => number_format(($star_number / $total_number) * 100, 2),
            ];
        }


        $result = [
            'loan_result'   => $loan_result, // 贷款人数统计
            'store_result'  => $store_result, // 存款人数统计
            'ratio_result'  => $ratio_result, // 占比统计
            'credit_result' => $credit_result, // 信用用户统计
            'town_result'   => $town_result,// 地区统计
            'star_result'   => $star_result,// 客户等级统计
        ];


        $this->success('获取成功', $result);
    }


}
