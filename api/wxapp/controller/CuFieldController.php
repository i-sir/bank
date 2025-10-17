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
     * 基本信息 列表
     * @OA\Post(
     *     tags={"基本信息配置"},
     *     path="/wxapp/cu_field/find_base_list",
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
     *   test_environment: http://bank.ikun:9090/api/wxapp/cu_field/find_base_list
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/cu_field/find_base_list
     *   api:  /wxapp/cu_field/find_base_list
     *   remark_name: 基本信息配置 详情
     *
     */
    public function find_base_list()
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


    /**
     * 业务信息 列表
     * @OA\Post(
     *     tags={"基本信息配置"},
     *     path="/wxapp/cu_field/find_business_list",
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
     *    @OA\Parameter(
     *         name="store_bank_ids",
     *         in="query",
     *         description="存款银行id  数组[1,2]",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *    @OA\Parameter(
     *         name="loan_bank_ids",
     *         in="query",
     *         description="贷款银行id  数组[1,2]",
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
     *   test_environment: http://bank.ikun:9090/api/wxapp/cu_field/find_business_list
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/cu_field/find_business_list
     *   api:  /wxapp/cu_field/find_business_list
     *   remark_name: 业务信息 列表
     *
     */
    public function find_business_list()
    {
        $CuBankModel = new \initmodel\CuBankModel(); //银行管理   (ps:InitModel)
        $params      = $this->request->param();


        $store_bank_ids = $params['store_bank_ids'];
        $loan_bank_ids  = $params['loan_bank_ids'];


        /** 存款银行 **/
        if ($store_bank_ids) {
            $bank_list = [];
            foreach ($store_bank_ids as $key => $bank_id) {
                $bank_info   = $CuBankModel->where('id', '=', $bank_id)->find();
                $bank_list[] = [
                    'bank_id'      => $bank_id,
                    'bank_name'    => $bank_info['name'],
                    'is_level'     => $bank_info['is_level'],
                    'store_type'   => '',//存款方式
                    'store_amount' => '',//存款金额
                ];
            }

            //存款银行信息
            $store_bank_info = [
                'bank_list'         => $bank_list,
                'store_amount'      => 0,//存款总额
                'this_store_amount' => 0,//本行存款总额
                'bank_proportion'   => 0,//本行存款占比
            ];
        }


        /** 贷款银行 **/
        if ($loan_bank_ids) {
            $bank_list = [];
            foreach ($loan_bank_ids as $key => $bank_id) {
                $bank_info   = $CuBankModel->where('id', '=', $bank_id)->find();
                $bank_list[] = [
                    'bank_id'        => $bank_id,
                    'bank_name'      => $bank_info['name'],
                    'loan_amount'    => '',//贷款金额
                    'guarantee_type' => '',//担保方式
                    'end_time'       => '',//到期时间
                    'is_credit'      => '',//本行信用客户:1是,2否
                ];
            }

            $loan_bank_info = [
                'bank_list' => $bank_list
            ];
        }


        $result = [
            'store_bank_info' => $store_bank_info ?? [],
            'loan_bank_info'  => $loan_bank_info ?? [],
        ];

        $this->success("详情数据", $result);
    }


    /**
     * 处理存款信息
     * @OA\Post(
     *     tags={"基本信息配置"},
     *     path="/wxapp/cu_field/store_bank_info",
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="store_bank_info",
     *         in="query",
     *         description="存款信息完整数据",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://bank.ikun:9090/api/wxapp/cu_field/store_bank_info
     *   official_environment: http://xcxkf213.aubye.com/api/wxapp/cu_field/store_bank_info
     *   api:  /wxapp/cu_field/store_bank_info
     *   remark_name: 处理存款信息
     *
     */
    public function store_bank_info()
    {
        $params = $this->request->param();

        //测试数据
        //        $params['store_bank_info'] =
        //            [
        //                'bank_list' => [
        //                    [
        //                        'bank_id'      => 1,
        //                        'bank_name'    => '测试001',
        //                        'is_level'     => 1,
        //                        'store_type'   => '测试',//存款方式
        //                        'store_amount' => 88614,//存款金额
        //                    ], [
        //                        'bank_id'      => 2,
        //                        'bank_name'    => '测试002',
        //                        'is_level'     => 1,
        //                        'store_type'   => '测试22',//存款方式
        //                        'store_amount' => 12453,//存款金额
        //                    ],
        //                ],
        //                'store_amount'      => 0,//存款总额
        //                'this_store_amount' => 0,//本行存款总额
        //                'bank_proportion'         => 0,//本行存款占比
        //            ];

        //计算存款总额 本行存款占比
        $this_store_amount = 0;//本行存款总额
        $store_amount      = 0;//存款总额

        //银行列表
        $bank_list = $params['store_bank_info']['bank_list'];
        foreach ($bank_list as $key => $bank_info) {
            $store_amount += $bank_info['store_amount'] ?? 0;
            if ($bank_info['is_level'] == 1) {
                $this_store_amount += $bank_info['store_amount'];
            }
        }

        //计算本行存款占比（避免除零错误）
        if ($store_amount > 0) {
            $bank_proportion = ($this_store_amount / $store_amount) * 100;
        } else {
            $bank_proportion = 0;
        }

        //返回结果
        $result = [
            'bank_list'         => $bank_list,
            'this_store_amount' => $this_store_amount,//本行存款总额（这里修正了，原来写的是$store_amount）
            'store_amount'      => round($store_amount, 2),//存款总额
            'bank_proportion'   => round($bank_proportion, 2),//本行存款占比
        ];

        $this->success("详情数据", $result);
    }


}
