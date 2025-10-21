<?php

namespace app\admin\controller;


/**
 * @adminMenuRoot(
 *     "name"                =>"Statistics",
 *     "name_underline"      =>"statistics",
 *     "controller_name"     =>"Statistics",
 *     "table_name"          =>"statistics",
 *     "action"              =>"default",
 *     "parent"              =>"",
 *     "display"             => true,
 *     "order"               => 10000,
 *     "icon"                =>"none",
 *     "remark"              =>"统计管理",
 *     "author"              =>"",
 *     "create_time"         =>"2025-10-12 10:18:28",
 *     "version"             =>"1.0",
 *     "use"                 => new \app\admin\controller\StatisticsController();
 * )
 */


use think\facade\Db;
use cmf\controller\AdminBaseController;


class StatisticsController extends AdminBaseController
{


    /**
     * 首页列表数据
     * @adminMenu(
     *     'name'             => 'Statistics',
     *     'name_underline'   => 'statistics',
     *     'parent'           => 'index',
     *     'display'          => true,
     *     'hasView'          => true,
     *     'order'            => 10000,
     *     'icon'             => '',
     *     'remark'           => '统计管理',
     *     'param'            => ''
     * )
     */
    public function index()
    {


        return $this->fetch();


    }


    public function find_statistics()
    {

        // 初始化数据模型：用于后续数据库查询操作
        $CustomerModel = new \initmodel\CustomerModel(); // 客户信息模型（存储客户基本信息）
        $CuBankModel   = new \initmodel\CuBankModel();   // 银行管理模型（存储银行信息及分类）
        $CuVillageInit = new \init\CuVillageInit();//村庄管理   (ps:InitController)

        $params = $this->request->param(); // 获取请求参数：包含时间维度参数（date）等

        /**
         * 基础查询条件设置
         * 严格遵循格式：先声明空数组，再通过[]追加条件（便于后续扩展多条件）
         * 基础条件：过滤当前登录用户的数据（user_id = 当前用户ID）
         */
        $baseMap = []; // 基础条件数组（初始为空）
        if ($params['user_id']) $baseMap[] = ['user_id', '=', $params['user_id']]; // 追加用户ID条件
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
                'change_text'  => $trend > 0 ? "+{$changeCount}" : ($trend < 0 ? (string)$changeCount : '+0') // 显示文本（带符号）
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
            'loan_number'  => $loanTotal, // 贷款人数
            'loan_ratio'   => $loanRatio, // 贷款占比（百分比）
            'store_number' => $storeTotal,// 存款人数
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
         * 地区统计（按村庄）
         * 统计各乡镇的客户数量及占总客户数的百分比
         */
        $totalNumber = $CustomerModel->where($currentMap)->where('town_id', '=', $params['town_id'])->count(); // 当前时间段总客户数

        $villageMap   = [];
        $villageMap[] = ['pid', '=', $params['town_id']];
        $villageMap[] = ['is_show', '=', 1];
        $villageList  = $CuVillageInit->get_list($villageMap); // 获取所有乡镇列表
        $townResult   = []; // 地区统计结果集
        foreach ($villageList as $village) {
            // 乡镇过滤条件（严格遵循格式）
            $townFilter   = [];
            $townFilter[] = ['village_id', '=', $village['id']]; // 条件：村庄id
            $townFilter[] = ['town_id', '=', $params['town_id']]; // 条件：乡镇ID=当前乡镇ID

            // 统计当前乡镇的客户数量
            $count = $CustomerModel->where(array_merge($currentMap, $townFilter))->count();
            // 计算占比（避免除零错误）
            $percentage = $totalNumber > 0 ? number_format(($count / $totalNumber) * 100, 2) : 0;

            $townResult[] = [
                'id'         => $village['id'],
                'name'       => $village['name'], // 乡镇名称
                'number'     => $count, // 客户数量
                'percentage' => $percentage // 占比（百分比）
            ];
        }

        /**
         * 客户等级统计
         * 按客户等级（如星级）统计数量及占总客户数的百分比
         */
        $starList   = $parseConfig('customer_star', '/'); // 解析客户等级配置（如1=一星,2=二星）
        $totalNumber = $CustomerModel->where($currentMap)->count(); // 当前时间段总客户数
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


        /** 客户归属占比比例 **/
        $attributionList    = $parseConfig('customer_attribution', '/'); // 解析归属类型配置
        $attributionResult2 = []; // 归属类型统计结果集
        $totalNumber        = $CustomerModel->where($currentMap)->count(); // 总用户

        foreach ($attributionList as $key => $name) {
            // 归属类型过滤条件（严格遵循格式：先空数组再追加）
            $filterMap   = []; // 初始化过滤条件
            $filterMap[] = ['ascription', '=', $key]; // 追加条件：归属类型=当前循环的类型ID

            // 统计当前时间段该归属类型的客户数量（合并基础条件+时间条件+归属类型条件）
            $currentCount = $CustomerModel->where(array_merge($currentMap, $filterMap))->count();
            // 统计对比时间段该归属类型的客户数量
            $beforeCount = $CustomerModel->where(array_merge($beforeMap, $filterMap))->count();

            // 计算变化量和趋势
            $changeCount = $currentCount - $beforeCount; // 变化量（当前-对比）
            $trend       = 0; // 趋势标识（默认持平）
            if ($changeCount > 0) {
                $trend = 1; // 增长
            } elseif ($changeCount < 0) {
                $trend = -1; // 减少
            }

            // 计算百分比（避免除零错误）
            $percentage = $totalNumber > 0 ? round(($currentCount / $totalNumber) * 100, 2) : 0;

            // 组装当前归属类型的统计结果
            $attributionResult2[] = [
                'type'         => $key, // 归属类型ID
                'name'         => $name, // 归属类型名称
                'total'        => $currentCount, // 当前时间段数量
                'before_total' => $beforeCount, // 对比时间段数量
                'change_count' => abs($changeCount), // 变化量绝对值
                'change_trend' => $trend, // 趋势（1增长/-1减少/0持平）
                'change_text'  => $trend > 0 ? "+{$changeCount}" : ($trend < 0 ? (string)$changeCount : '+0'), // 显示文本（带符号）
                'percentage'   => $percentage // 当前数量占总用户数的百分比
            ];
        }


        // 组装所有统计结果并返回
        $this->success('获取成功', '', [
            'customer_result'     => $customerResult, // 客户类型统计
            'attribution_result'  => $attributionResult, // 客户归属占比
            'attribution_result2' => $attributionResult2,// 归属类型统计
            'loan_result'         => $loanResult, // 贷款人数统计
            'store_result'        => $storeResult, // 存款人数统计
            'ratio_result'        => $ratioResult, // 贷款/存款占比
            'credit_result'       => $creditResult, // 信用用户统计
            'town_result'         => $townResult, // 地区统计
            'star_result'         => $starResult // 客户等级统计
        ]);
    }


    public function find_town_list()
    {
        $CuTownInit  = new \init\CuTownInit();//乡镇管理   (ps:InitController)
        $CuTownModel = new \initmodel\CuTownModel(); //乡镇管理   (ps:InitModel)

        /** 获取参数 **/
        $params = $this->request->param();


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

        $this->success("请求成功!", '', $result);
    }

}
