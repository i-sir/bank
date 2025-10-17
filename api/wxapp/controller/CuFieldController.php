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
        $params = $this->request->param();


        $store_bank_ids = $params['store_bank_ids'];
        $loan_bank_ids  = $params['loan_bank_ids'];


        /** 存款银行 **/
        if ($store_bank_ids) {
            $bank_list = [];
            foreach ($store_bank_ids as $key => $bank_id) {
                $bank_info = $CuBankModel->where('id','=',$bank_id)->find();
                $bank_list[] = [
                    'bank_id'      => $bank_id,
                    'bank_name'    => $bank_info['name'],
                    'store_type'   => '',//存款方式
                    'store_amount' => '',//存款金额
                ];
            }

            //存款银行信息
            $store_bank_info = [
                'bank_list'          => $bank_list,
                'store_total_amount' => 0,//存款总额
                'bank_proportion'    => 0,//本行存款占比
            ];
        }


        /** 贷款银行 **/
        if ($loan_bank_ids) {
            $bank_list = [];
            foreach ($loan_bank_ids as $key => $bank_id) {
                $bank_info = $CuBankModel->where('id','=',$bank_id)->find();
                $bank_list[] = [
                    'bank_id'        => $bank_id,
                    'bank_name'      => $bank_info['name'],
                    'loan_amount'    => '',//贷款金额
                    'guarantee_type' => '',//担保方式
                    'end_time'       => '',//到期时间
                    'is_credit'      => '',//本行信用客户:1是,2否
                ];
            }
        }




    }


}
