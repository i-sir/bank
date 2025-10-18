<?php

namespace init;


/**
 * @Init(
 *     "name"            =>"Customer",
 *     "name_underline"  =>"customer",
 *     "table_name"      =>"customer",
 *     "model_name"      =>"CustomerModel",
 *     "remark"          =>"客户信息记录",
 *     "author"          =>"",
 *     "create_time"     =>"2025-10-18 09:44:10",
 *     "version"         =>"1.0",
 *     "use"             => new \init\CustomerInit();
 * )
 */

use think\facade\Db;
use app\admin\controller\ExcelController;


class CustomerInit extends Base
{

    public $type       = [1 => '农户', 2 => '牧户', 3 => '个体工商户', 4 => '公职人员', 5 => '政府全资或控股企业正式职工', 6 => '社区居民', 7 => '民营小微企业', 8 => '合作经济组织', 9 => '政府全资或控股企业', 10 => '行政事业单位', 11 => '其他群体'];//客户类型
    public $ascription = [1 => '本行客户', 2 => '他行客户', 3 => '交叉客户', 4 => '空白客户'];//客户归属
    public $level      = [1 => '战略客户', 2 => '重点客户', 3 => '价值客户', 4 => '一般客户', 5 => '长尾客户'];//客户分层
    public $star       = [1 => '准星级', 2 => '二星级', 3 => '三星级', 4 => '四星级', 5 => '五星级', 6 => '六星级', 7 => '七星级', 8 => '私人银行级'];//星级
    public $is_credit  = [1 => '是', 2 => '否'];//本行信用客户

    // 1. 定义type与名称标签的映射关系
    public $nameLabelMap = [
        1  => '户主姓名',
        2  => '户主姓名',
        3  => '商户名称',
        4  => '姓名',
        5  => '姓名',
        6  => '户主姓名',
        7  => '企业名称',
        8  => '主体名称',
        9  => '企业名称',
        10 => '单位名称',
        11 => '客户名称'
    ];

    // 2. 定义type与证件标签的映射关系
    public $idLabelMap = [
        1  => '身份证号码',
        2  => '身份证号码',
        3  => '身份证号码',
        4  => '身份证号码',
        5  => '身份证号码',
        6  => '身份证号码',
        7  => '统一社会信用代码',
        8  => '统一社会信用代码',
        9  => '统一社会信用代码',
        10 => '统一社会信用代码',
        11 => '身份证号码'
    ];


    protected $Field         = "*";//过滤字段,默认全部
    protected $Limit         = 100000;//如不分页,展示条数
    protected $PageSize      = 15;//分页每页,数据条数
    protected $Order         = "id desc";//排序
    protected $InterfaceType = "api";//接口类型:admin=后台,api=前端
    protected $DataFormat    = "find";//数据格式,find详情,list列表

    //本init和model
    public function _init()
    {
        $CustomerInit  = new \init\CustomerInit();//客户信息记录   (ps:InitController)
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录  (ps:InitModel)
    }

    /**
     * 处理公共数据
     * @param array $item   单条数据
     * @param array $params 参数
     * @return array|mixed
     */
    public function common_item($item = [], $params = [])
    {
        $CuVillageModel = new \initmodel\CuVillageModel(); //村庄管理   (ps:InitModel)
        $CuTownModel    = new \initmodel\CuTownModel(); //乡镇管理   (ps:InitModel)
        $MemberInit     = new \init\MemberInit();//会员管理


        //接口类型
        if ($params['InterfaceType']) $this->InterfaceType = $params['InterfaceType'];
        //数据格式
        if ($params['DataFormat']) $this->DataFormat = $params['DataFormat'];

        $user_info         = $MemberInit->get_find(['id' => $item['user_id']]);
        $item['user_info'] = $user_info;

        /** 数据格式(公共部分),find详情&&list列表 共存数据 **/
        $village_info         = $CuVillageModel->where('id', '=', $item['village_id'])->find();
        $item['village_info'] = $village_info;
        $item['village_name'] = $village_info['name'];

        $town_info         = $CuTownModel->where('id', '=', $item['town_id'])->find();
        $item['town_info'] = $town_info;
        $item['town_name'] = $town_info['name'];


        /** 处理文字描述 **/
        $item['type_name']       = $this->type[$item['type']];//客户类型
        $item['ascription_name'] = $this->ascription[$item['ascription']];//客户归属
        $item['level_name']      = $this->level[$item['level']];//客户分层
        $item['star_name']       = $this->star[$item['star']];//星级
        $item['is_credit_name']  = $this->is_credit[$item['is_credit']];//本行信用客户
        $item['username_text']   = $this->nameLabelMap[$item['type']] ?? ''; // 名称标签（如“户主姓名”）
        $item['id_number_text']  = $this->idLabelMap[$item['type']] ?? '';     // 证件标签（如“身份证号码”）


        //处理json数据
        if ($item['information_info']) $item['information_info'] = json_decode($item['information_info'], true);
        if ($item['loan_bank_info']) $item['loan_bank_info'] = json_decode($item['loan_bank_info'], true);
        if ($item['store_bank_info']) $item['store_bank_info'] = json_decode($item['store_bank_info'], true);


        /** 处理数据 **/
        if ($this->InterfaceType == 'api') {
            /** api处理文件 **/


            /** 处理富文本 **/


            if ($this->DataFormat == 'find') {
                /** find详情数据格式 **/


            } else {
                /** list列表数据格式 **/

            }


        } else {
            /** admin处理文件 **/


            if ($this->DataFormat == 'find') {
                /** find详情数据格式 **/


                /** 处理富文本 **/


            } else {
                /** list列表数据格式 **/

            }

        }


        /** 导出数据处理 **/
        if (isset($params["is_export"]) && $params["is_export"]) {
            $item["create_time"] = date("Y-m-d H:i:s", $item["create_time"]);
            $item["update_time"] = date("Y-m-d H:i:s", $item["update_time"]);
        }

        return $item;
    }


    /**
     * 获取列表
     * @param $where  条件
     * @param $params 扩充参数 order=排序  field=过滤字段 limit=限制条数  InterfaceType=admin|api后端,前端
     * @return false|mixed
     */
    public function get_list($where = [], $params = [])
    {
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录  (ps:InitModel)


        /** 查询数据 **/
        $result = $CustomerModel
            ->where($where)
            ->order($params['order'] ?? $this->Order)
            ->field($params['field'] ?? $this->Field)
            ->limit($params["limit"] ?? $this->Limit)
            ->select()
            ->each(function ($item, $key) use ($params) {

                /** 处理公共数据 **/
                $item = $this->common_item($item, $params);

                return $item;
            });

        /** 根据接口类型,返回不同数据类型 **/
        if ($params['InterfaceType']) $this->InterfaceType = $params['InterfaceType'];
        if ($this->InterfaceType == 'api' && empty(count($result))) return false;

        return $result;
    }


    /**
     * 分页查询
     * @param $where  条件
     * @param $params 扩充参数 order=排序  field=过滤字段 page_size=每页条数  InterfaceType=admin|api后端,前端
     * @return mixed
     */
    public function get_list_paginate($where = [], $params = [])
    {
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录  (ps:InitModel)


        /** 查询数据 **/
        $result = $CustomerModel
            ->where($where)
            ->order($params['order'] ?? $this->Order)
            ->field($params['field'] ?? $this->Field)
            ->paginate(["list_rows" => $params["page_size"] ?? $this->PageSize, "query" => $params])
            ->each(function ($item, $key) use ($params) {

                /** 处理公共数据 **/
                $item = $this->common_item($item, $params);

                return $item;
            });

        /** 根据接口类型,返回不同数据类型 **/
        if ($params['InterfaceType']) $this->InterfaceType = $params['InterfaceType'];
        if ($this->InterfaceType == 'api' && $result->isEmpty()) return false;


        return $result;
    }

    /**
     * 获取列表
     * @param $where  条件
     * @param $params 扩充参数 order=排序  field=过滤字段 limit=限制条数  InterfaceType=admin|api后端,前端
     * @return false|mixed
     */
    public function get_join_list($where = [], $params = [])
    {
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录  (ps:InitModel)

        /** 查询数据 **/
        $result = $CustomerModel
            ->alias('a')
            ->join('member b', 'a.user_id = b.id')
            ->where($where)
            ->order('a.id desc')
            ->field('a.*')
            ->paginate(["list_rows" => $params["page_size"] ?? $this->PageSize, "query" => $params])
            ->each(function ($item, $key) use ($params) {

                /** 处理公共数据 **/
                $item = $this->common_item($item, $params);


                return $item;
            });

        /** 根据接口类型,返回不同数据类型 **/
        if ($params['InterfaceType']) $this->InterfaceType = $params['InterfaceType'];
        if ($this->InterfaceType == 'api' && empty(count($result))) return false;

        return $result;
    }


    /**
     * 获取详情
     * @param $where     条件 或 id值
     * @param $params    扩充参数 field=过滤字段  InterfaceType=admin|api后端,前端
     * @return false|mixed
     */
    public function get_find($where = [], $params = [])
    {
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录  (ps:InitModel)

        /** 可直接传id,或者where条件 **/
        if (is_string($where) || is_int($where)) $where = ["id" => (int)$where];
        if (empty($where)) return false;

        /** 查询数据 **/
        $item = $CustomerModel
            ->where($where)
            ->order($params['order'] ?? $this->Order)
            ->field($params['field'] ?? $this->Field)
            ->find();


        if (empty($item)) return false;


        /** 处理公共数据 **/
        $item = $this->common_item($item, $params);


        return $item;
    }


    /**
     * 前端  编辑&添加
     * @param $params 参数
     * @param $where  where条件
     * @return void
     */
    public function api_edit_post($params = [], $where = [])
    {
        $result = false;

        /** 接口提交,处理数据 **/


        $result = $this->edit_post($params, $where);//api提交

        return $result;
    }


    /**
     * 后台  编辑&添加
     * @param $model  类
     * @param $params 参数
     * @param $where  更新提交(编辑数据使用)
     * @return void
     */
    public function admin_edit_post($params = [], $where = [])
    {
        $result = false;

        /** 后台提交,处理数据 **/


        $result = $this->edit_post($params, $where);//admin提交

        return $result;
    }


    /**
     * 提交 编辑&添加
     * @param $params
     * @param $where where条件(或传id)
     * @return void
     */
    public function edit_post($params, $where = [])
    {
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录  (ps:InitModel)


        /** 查询详情数据 && 需要再打开 **/
        if (!empty($params["id"])) $item = $this->get_find(["id" => $params["id"]], ["DataFormat" => "list"]);
        if (empty($params["id"]) && !empty($where)) $item = $this->get_find($where, ["DataFormat" => "list"]);

        /** 可直接传id,或者where条件 **/
        if (is_string($where) || is_int($where)) $where = ["id" => (int)$where];


        /** 公共提交,处理数据 **/
        if ($params['information_info']) $params['information_info'] = json_encode($params['information_info'], JSON_UNESCAPED_UNICODE);
        if ($params['loan_bank_info']) $params['loan_bank_info'] = json_encode($params['loan_bank_info'], JSON_UNESCAPED_UNICODE);
        if ($params['store_bank_info']) $params['store_bank_info'] = json_encode($params['store_bank_info'], JSON_UNESCAPED_UNICODE);

        if ($params['loan_bank_ids']) $params['loan_bank_ids'] = $this->setParams($params['loan_bank_ids']);
        if ($params['store_bank_ids']) $params['store_bank_ids'] = $this->setParams($params['store_bank_ids']);

        if (!empty($where)) {
            //传入where条件,根据条件更新数据
            $params["update_time"] = time();
            $result                = $CustomerModel->where($where)->strict(false)->update($params);
            if ($result) $result = $item["id"];
        } elseif (!empty($params["id"])) {
            //如传入id,根据id编辑数据
            $params["update_time"] = time();
            $result                = $CustomerModel->where("id", "=", $params["id"])->strict(false)->update($params);
            if ($result) $result = $item["id"];
        } else {
            //无更新条件则添加数据
            $params["create_time"] = time();
            $result                = $CustomerModel->strict(false)->insert($params, true);
        }

        return $result;
    }


    /**
     * 提交(副本,无任何操作,不查询详情,不返回id) 编辑&添加
     * @param $params
     * @param $where where 条件(或传id)
     * @return void
     */
    public function edit_post_two($params, $where = [])
    {
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录  (ps:InitModel)


        /** 可直接传id,或者where条件 **/
        if (is_string($where) || is_int($where)) $where = ["id" => (int)$where];


        /** 公共提交,处理数据 **/


        if (!empty($where)) {
            //传入where条件,根据条件更新数据
            $params["update_time"] = time();
            $result                = $CustomerModel->where($where)->strict(false)->update($params);
        } elseif (!empty($params["id"])) {
            //如传入id,根据id编辑数据
            $params["update_time"] = time();
            $result                = $CustomerModel->where("id", "=", $params["id"])->strict(false)->update($params);
        } else {
            //无更新条件则添加数据
            $params["create_time"] = time();
            $result                = $CustomerModel->strict(false)->insert($params);
        }

        return $result;
    }


    /**
     * 删除数据 软删除
     * @param $id     传id  int或array都可以
     * @param $type   1软删除 2真实删除
     * @param $params 扩充参数
     * @return void
     */
    public function delete_post($id, $type = 1, $params = [])
    {
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录  (ps:InitModel)


        if ($type == 1) $result = $CustomerModel->destroy($id);//软删除 数据表字段必须有delete_time
        if ($type == 2) $result = $CustomerModel->destroy($id, true);//真实删除

        return $result;
    }


    /**
     * 后台批量操作
     * @param $id
     * @param $params 修改值
     * @return void
     */
    public function batch_post($id, $params = [])
    {
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录  (ps:InitModel)

        $where   = [];
        $where[] = ["id", "in", $id];//$id 为数组


        $params["update_time"] = time();
        $result                = $CustomerModel->where($where)->strict(false)->update($params);//修改状态

        return $result;
    }


    /**
     * 后台  排序
     * @param $list_order 排序
     * @param $params     扩充参数
     * @return void
     */
    public function list_order_post($list_order, $params = [])
    {
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录   (ps:InitModel)

        foreach ($list_order as $k => $v) {
            $where   = [];
            $where[] = ["id", "=", $k];
            $result  = $CustomerModel->where($where)->strict(false)->update(["list_order" => $v, "update_time" => time()]);//排序
        }

        return $result;
    }


    /**
     * 导出数据
     * @param array $where 条件
     */
    public function export_excel($where = [], $params = [])
    {
        $CustomerInit  = new \init\CustomerInit();//客户信息记录   (ps:InitController)
        $CustomerModel = new \initmodel\CustomerModel(); //客户信息记录  (ps:InitModel)

        $result = $CustomerInit->get_list($where, $params);

        $result = $result->toArray();
        foreach ($result as $k => &$item) {

            //订单号过长问题
            if ($item["order_num"]) $item["order_num"] = $item["order_num"] . "\t";

            //图片链接 可用默认浏览器打开   后面为展示链接名字 --单独,多图特殊处理一下
            if ($item["image"]) $item["image"] = '=HYPERLINK("' . cmf_get_asset_url($item['image']) . '","图片.png")';


            //用户信息
            $user_info        = $item['user_info'];
            $item['userInfo'] = "(ID:{$user_info['id']}) {$user_info['nickname']}  {$user_info['phone']}";


            //背景颜色
            if ($item['unit'] == '测试8') $item['BackgroundColor'] = 'red';
        }

        $headArrValue = [
            ["rowName" => "ID", "rowVal" => "id", "width" => 10],
            ["rowName" => "用户信息", "rowVal" => "userInfo", "width" => 30],
            ["rowName" => "名字", "rowVal" => "name", "width" => 20],
            ["rowName" => "年龄", "rowVal" => "age", "width" => 20],
            ["rowName" => "测试", "rowVal" => "test", "width" => 20],
            ["rowName" => "创建时间", "rowVal" => "create_time", "width" => 30],
        ];


        //副标题 纵单元格
        //        $subtitle = [
        //            ["rowName" => "列1", "acrossCells" => count($headArrValue)/2],
        //            ["rowName" => "列2", "acrossCells" => count($headArrValue)/2],
        //        ];

        $Excel = new ExcelController();
        $Excel->excelExports($result, $headArrValue, ["fileName" => "客户信息记录"]);
    }

}
