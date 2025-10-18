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
     *   test_environment: http://bank.ikun:9090/api/wxapp/customer/find_statistics
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/customer/find_statistics
     *   api:  /wxapp/customer/find_statistics
     *   remark_name: 统计
     *
     */
    public function find_statistics()
    {
        $this->checkAuth(); // 权限校验：验证当前用户是否有权限访问该统计接口

        // 初始化数据模型：用于后续数据库查询操作
        $CustomerModel = new \initmodel\CustomerModel(); // 客户信息模型（存储客户基本信息）
        $CuBankModel   = new \initmodel\CuBankModel();   // 银行管理模型（存储银行信息及分类）
        $CuTownInit    = new \init\CuTownInit();         // 乡镇管理初始化类（获取乡镇列表数据）

        $params = $this->request->param(); // 获取请求参数：包含时间维度参数（date）等

        /**
         * 基础查询条件设置
         * 严格遵循格式：先声明空数组，再通过[]追加条件（便于后续扩展多条件）
         * 基础条件：过滤当前登录用户的数据（user_id = 当前用户ID）
         */
        $baseMap   = []; // 基础条件数组（初始为空）
        $baseMap[] = ['user_id', '=', $this->user_id]; // 追加用户ID条件
        if ($params['type']) $baseMap[] = ['type', '=', $params['type']];

        /**
         * 时间维度条件初始化
         * beforeMap：对比时间段查询条件（继承基础条件）
         * currentMap：当前时间段查询条件（继承基础条件）
         * 通过循环复制基础条件，确保格式统一（先空数组再追加）
         */
        $beforeMap = []; // 对比时间段条件（初始为空）
        foreach ($baseMap as $item) {
            $beforeMap[] = $item; // 复制基础条件到对比时间段
        }

        $currentMap = []; // 当前时间段条件（初始为空）
        foreach ($baseMap as $item) {
            $currentMap[] = $item; // 复制基础条件到当前时间段
        }

        /**
         * 时间范围配置映射
         * 键值对应date参数（1=昨天对比今天，2=上周对比本周等）
         * 每个配置包含对比时间段（before）和当前时间段（current）的起止时间戳
         * 用于替代冗长的if-else判断，简化时间条件设置
         */
        $dateConfig = [
            1 => [
                'before'  => [strtotime('yesterday'), strtotime('yesterday 23:59:59')], // 对比：昨天（00:00:00 - 23:59:59）
                'current' => [strtotime('today'), time()] // 当前：今天（00:00:00 - 当前时间）
            ],
            2 => [
                'before'  => [strtotime('last week monday'), strtotime('last week sunday 23:59:59')], // 对比：上周（周一至周日）
                'current' => [strtotime('this week monday'), time()] // 当前：本周（周一至当前时间）
            ],
            3 => [
                'before'  => [strtotime('first day of last month'), strtotime('last day of last month 23:59:59')], // 对比：上月（月初至月末）
                'current' => [strtotime('first day of this month'), time()] // 当前：本月（月初至当前时间）
            ],
            4 => [
                'before'  => [strtotime('first day of january last year'), strtotime('last day of december last year 23:59:59')], // 对比：上年（全年）
                'current' => [strtotime('first day of january this year'), time()] // 当前：本年（年初至当前时间）
            ]
        ];

        // 根据请求参数中的date值，为时间条件追加具体范围（严格遵循先空数组再追加的格式）
        if (isset($dateConfig[$params['date']])) {
            $beforeMap[]  = ['create_time', 'between', $dateConfig[$params['date']]['before']]; // 对比时间段：创建时间在配置范围内
            $currentMap[] = ['create_time', 'between', $dateConfig[$params['date']]['current']]; // 当前时间段：创建时间在配置范围内
        }

        /**
         * 配置解析匿名函数
         * 统一处理系统配置中"key=value/key=value"格式的数据（如客户类型、归属、等级等）
         * @param string $configKey 配置键名（如customer_type）
         * @param string $delimiter 配置项分隔符（如/）
         * @return array 解析后格式：[key => value, ...]（key为整数，便于查询匹配）
         */
        $parseConfig = function ($configKey, $delimiter) {
            $list = []; // 初始化解析结果数组
            // 从系统配置获取数据并按分隔符拆分（如"1=农户/2=商户"拆分为["1=农户", "2=商户"]）
            foreach ($this->getParams(cmf_config($configKey), $delimiter) as $item) {
                list($key, $value) = explode('=', $item); // 拆分键值对（如"1=农户"拆分为key=1，value=农户）
                $list[(int)$key] = $value; // 转换key为整数并存储
            }
            return $list;
        };

        /**
         * 客户类型统计
         * 按客户类型（如农户、商户、企业）统计：
         * - 当前时间段数量
         * - 对比时间段数量
         * - 数量变化量、趋势及显示文本
         */
        $typeList       = $parseConfig('customer_type', '/'); // 解析客户类型配置（如1=农户,2=商户）
        $customerResult = []; // 客户类型统计结果集
        foreach ($typeList as $key => $name) {
            // 客户类型过滤条件（严格遵循格式：先空数组再追加）
            $filterMap   = []; // 初始化过滤条件
            $filterMap[] = ['type', '=', $key]; // 追加条件：客户类型=当前循环的类型ID

            // 统计当前时间段该类型的客户数量（合并基础条件+时间条件+类型条件）
            $currentCount = $CustomerModel->where(array_merge($currentMap, $filterMap))->count();
            // 统计对比时间段该类型的客户数量
            $beforeCount = $CustomerModel->where(array_merge($beforeMap, $filterMap))->count();

            // 计算变化量和趋势
            $changeCount = $currentCount - $beforeCount; // 变化量（当前-对比）
            $trend       = 0; // 趋势标识（默认持平）
            if ($changeCount > 0) {
                $trend = 1; // 增长
            } elseif ($changeCount < 0) {
                $trend = -1; // 减少
            }

            // 组装当前类型的统计结果
            $customerResult[] = [
                'type'         => $key, // 类型ID
                'name'         => $name, // 类型名称
                'total'        => $currentCount, // 当前时间段数量
                'before_total' => $beforeCount, // 对比时间段数量
                'change_count' => abs($changeCount), // 变化量绝对值
                'change_trend' => $trend, // 趋势（1增长/-1减少/0持平）
                'change_text'  => $trend > 0 ? "+{$changeCount}" : ($trend < 0 ? (string)$changeCount : '0') // 显示文本（带符号）
            ];
        }

        /**
         * 客户归属占比统计（用于饼图展示）
         * 按归属类型（如自有客户、推荐客户等）统计当前时间段的客户数量
         */
        $attributionList   = $parseConfig('customer_attribution', '/'); // 解析归属类型配置
        $attributionResult = []; // 饼图数据格式（符合图表展示要求）
        foreach ($attributionList as $key => $name) {
            // 归属类型过滤条件（严格遵循格式）
            $filterMap   = [];
            $filterMap[] = ['ascription', '=', $key]; // 条件：归属类型=当前循环的类型ID

            // 统计当前归属类型的客户数量
            $count                 = $CustomerModel->where(array_merge($currentMap, $filterMap))->count();
            $series_list['data'][] = [
                'name'  => $name, // 归属类型名称
                'value' => $count // 客户数量
            ];
        }
        $attributionResult['series'] = [$series_list];




        /**
         * 银行基础数据查询
         * 区分"本行"和"其他银行"，为后续贷款/存款统计提供判断依据
         */
        // 查询本行信息（is_level=1标识本行，is_show=1标识启用）
        $bankMap   = []; // 本行查询条件（严格遵循格式）
        $bankMap[] = ['is_level', '=', 1]; // 条件：银行级别=1（本行）
        $bankMap[] = ['is_show', '=', 1]; // 条件：状态=启用
        $bankInfo  = $CuBankModel->where($bankMap)->find() ?: []; // 获取本行信息（默认空数组避免报错）

        // 查询其他银行ID列表（is_level=2标识其他银行）
        $otherBankMap   = []; // 其他银行查询条件（严格遵循格式）
        $otherBankMap[] = ['is_level', '=', 2]; // 条件：银行级别=2（其他银行）
        $otherBankMap[] = ['is_show', '=', 1]; // 条件：状态=启用
        $otherBankIds   = $CuBankModel->where($otherBankMap)->column('id') ?: []; // 其他银行ID数组（默认空数组）
        $bankId         = $bankInfo['id'] ?? 0; // 本行ID（默认0避免后续判断报错）

        /**
         * 银行统计通用逻辑（匿名函数复用）
         * 统一处理贷款/存款的银行分布统计：
         * - 本行数量：在本行办理业务的客户数
         * - 其他银行数量：在其他银行办理业务的客户数
         * - 总数：办理该业务的总客户数
         * @param string $field        业务字段（loan_bank_ids=贷款银行，store_bank_ids=存款银行）
         * @param array  $customerList 客户列表数据（避免重复查询数据库）
         * @return array 统计结果：本行数量、其他银行数量、总数
         */
        $calcBankStats = function ($field, $customerList) use ($bankId, $otherBankIds) {
            $thisBank  = 0; // 本行数量
            $otherBank = 0; // 其他银行数量
            $total     = 0; // 总数
            foreach ($customerList as $item) {
                if (empty($item[$field])) {
                    continue; // 若客户未填写该业务的银行信息，跳过
                }
                // 拆分客户填写的银行ID列表（如"1,3,5"拆分为[1,3,5]）
                $ids = $this->getParams($item[$field], ',');
                // 若包含本行ID，本行数量+1
                $thisBank += in_array($bankId, $ids) ? 1 : 0;
                // 若包含其他银行ID（判断数组交集），其他银行数量+1
                $otherBank += !empty(array_intersect($otherBankIds, $ids)) ? 1 : 0;
                $total++; // 总数+1
            }
            return [
                'this_bank_number'  => $thisBank,
                'other_bank_number' => $otherBank,
                'total_bank_number' => $total
            ];
        };

        // 预查询当前时间段的客户列表（避免贷款/存款统计时重复查询数据库，提升性能）
        $customerList = $CustomerModel->where($currentMap)->select();

        /**
         * 贷款人数统计
         * 调用通用银行统计逻辑，统计贷款业务的银行分布
         */
        $loanResult = $calcBankStats('loan_bank_ids', $customerList);

        /**
         * 存款人数统计
         * 调用通用银行统计逻辑，统计存款业务的银行分布
         */
        $storeResult = $calcBankStats('store_bank_ids', $customerList);

        /**
         * 贷款/存款占比统计
         * 计算贷款人数、存款人数占总人数（贷款+存款）的百分比
         */
        $loanTotal   = $loanResult['total_bank_number'] ?? 0; // 贷款总人数
        $storeTotal  = $storeResult['total_bank_number'] ?? 0; // 存款总人数
        $totalPeople = $loanTotal + $storeTotal; // 总人数（贷款+存款）

        $loanRatio  = 0; // 贷款人数占比（默认0）
        $storeRatio = 0; // 存款人数占比（默认0）
        if ($totalPeople > 0) { // 避免除零错误（当总人数为0时不计算）
            $loanRatio  = number_format(($loanTotal / $totalPeople) * 100, 2); // 保留2位小数
            $storeRatio = number_format(($storeTotal / $totalPeople) * 100, 2);
        }
        $ratioResult = [
            'loan_ratio'   => $loanRatio, // 贷款占比（百分比）
            'store_ratio'  => $storeRatio, // 存款占比（百分比）
            'total_people' => $totalPeople // 总人数（参考用）
        ];

        /**
         * 信用用户统计
         * 统计信用用户数量及占贷款总人数的百分比
         */
        // 信用用户过滤条件（is_credit=1标识信用用户）
        $creditMap   = []; // 严格遵循格式
        $creditMap[] = ['is_credit', '=', 1];
        // 统计当前时间段信用用户数量（合并基础条件+时间条件+信用条件）
        $creditNumber = $CustomerModel->where(array_merge($currentMap, $creditMap))->count();

        $creditRatio = 0; // 信用用户占比（默认0）
        if ($loanTotal > 0) { // 避免除零错误（当贷款人数为0时不计算）
            $creditRatio = number_format(($creditNumber / $loanTotal) * 100, 2); // 保留2位小数
        }
        $creditResult = [
            'credit_number'      => $creditNumber, // 信用用户数量
            'loan_credit_number' => $loanTotal, // 贷款总人数
            'credit_ratio'       => $creditRatio // 信用用户占比（百分比）
        ];

        /**
         * 地区统计（按乡镇）
         * 统计各乡镇的客户数量及占总客户数的百分比
         */
        $totalNumber = $CustomerModel->where($currentMap)->count(); // 当前时间段总客户数
        $townList    = $CuTownInit->get_list(); // 获取所有乡镇列表
        $townResult  = []; // 地区统计结果集
        foreach ($townList as $town) {
            // 乡镇过滤条件（严格遵循格式）
            $townFilter   = [];
            $townFilter[] = ['town_id', '=', $town['id']]; // 条件：乡镇ID=当前乡镇ID

            // 统计当前乡镇的客户数量
            $count = $CustomerModel->where(array_merge($currentMap, $townFilter))->count();
            // 计算占比（避免除零错误）
            $percentage = $totalNumber > 0 ? number_format(($count / $totalNumber) * 100, 2) : 0;

            $townResult[] = [
                'name'       => $town['name'], // 乡镇名称
                'number'     => $count, // 客户数量
                'percentage' => $percentage // 占比（百分比）
            ];
        }

        /**
         * 客户等级统计
         * 按客户等级（如星级）统计数量及占总客户数的百分比
         */
        $starList   = $parseConfig('customer_star', '/'); // 解析客户等级配置（如1=一星,2=二星）
        $starResult = []; // 等级统计结果集
        foreach ($starList as $key => $name) {
            // 等级过滤条件（严格遵循格式）
            $starFilter   = [];
            $starFilter[] = ['star', '=', $key]; // 条件：客户等级=当前等级ID

            // 统计当前等级的客户数量
            $count = $CustomerModel->where(array_merge($currentMap, $starFilter))->count();
            // 计算占比（避免除零错误）
            $percentage = $totalNumber > 0 ? number_format(($count / $totalNumber) * 100, 2) : 0;

            $starResult[] = [
                'name'       => $name, // 等级名称
                'number'     => $count, // 客户数量
                'percentage' => $percentage // 占比（百分比）
            ];
        }

        // 组装所有统计结果并返回
        $this->success('获取成功', [
            'customer_result'    => $customerResult, // 客户类型统计
            'attribution_result' => $attributionResult, // 客户归属占比
            'loan_result'        => $loanResult, // 贷款人数统计
            'store_result'       => $storeResult, // 存款人数统计
            'ratio_result'       => $ratioResult, // 贷款/存款占比
            'credit_result'      => $creditResult, // 信用用户统计
            'town_result'        => $townResult, // 地区统计
            'star_result'        => $starResult // 客户等级统计
        ]);
    }

}
