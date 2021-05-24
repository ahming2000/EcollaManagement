<?php

namespace App\Http\Controllers\ChangingLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangingLogController extends Controller
{
    public function index()
    {

        $currentVersion = 'v1.5.2 Beta';
        $updateDate = '2021/05/22';
        $color = 'orange'; // Beta: orange, Release: green


        $logs = [

//            new LogClass('v1.6', [
//
//                new Log('v1.6.0', 'N/A', [
//                    new LogDetail('功能', [
//                        '添加了账号权限功能',
//                    ]),
//                ]),
//            ]),

            new LogClass('v1.5', [

                new Log('v1.5.2', '2021/05/22', [
                    new LogDetail('优化',[
                        '优化了顾客购物车界面选择订单模式的界面',
                    ]),
                ]),

                new Log('v1.5.1', '2021/05/19', [
                    new LogDetail('优化',[
                        '修复了订单收据下载功能下载缓慢的问题',
                    ]),
                ]),

                new Log('v1.5.0', '2021/05/17', [
                    new LogDetail('功能', [
                        '添加了订单收据下载功能',
                        '添加了商品搜索功能（商品管理）',
                        '添加了类别分类过滤功能（商品管理）',
                    ]),
                    new LogDetail('页面更新',[
                        '添加了广告图片',
                    ]),
                ]),
            ]),

            new LogClass('v1.4', [

                new Log('v1.4.9', '2021/05/15', [
                    new LogDetail('优化',[
                        '修复了商品保存后没有进行上架的问题',
                        ' 修复了商品保存后没有显示保存报告的问题',
                    ]),
                ]),

                new Log('v1.4.8', '2021/05/14', [
                    new LogDetail('优化',[
                        '修复了商品类别设定在添加新的类别之后无法删除的问题',
                        '优化了商品，类别过滤功能（商品类别过滤现在不显示0个商品的类别）',
                    ]),
                    new LogDetail('页面更新',[
                        '顾客商品主页排序更改为逆序',
                    ]),
                ]),

                new Log('v1.4.7', '2021/05/14', [
                    new LogDetail('优化',[
                        '优化了图片加载的时间',
                        '修复了在商品界面，没有图片的商品，点击后，没有办法进入商品界面的问题',
                    ]),
                    new LogDetail('页面更新',[
                        '调整了主页显示界面的位置',
                    ]),
                ]),

                new Log('v1.4.6', '2021/05/14', [
                    new LogDetail('优化',[
                        '优化了主页的显示界面',
                    ]),
                    new LogDetail('页面更新',[
                        '更改了顾客界面的颜色（部分不正常的界面显示需要清除浏览器缓存来修复错误）',
                    ]),
                ]),

                new Log('v1.4.5', '2021/05/14', [
                    new LogDetail('优化',[
                        '修复了商品规格输入与其他商品或同商品有相同的货号时，出现内部错误的问题',
                        '优化了装饰代码（部分不正常的装饰需要清除浏览器缓存来修复错误）',
                    ]),
                ]),

                new Log('v1.4.4', '2021/05/13', [
                    new LogDetail('优化',[
                        '修复了商品类别在其他商品资料验证失败之后，之前的类别选项没有保留的问题（商品编辑）',
                        '优化了装饰代码（部分不正常的装饰需要清除浏览器缓存来修复错误）',
                    ]),
                    new LogDetail('页面更新',[
                        '添加了新的主页展示图',
                    ]),
                ]),

                new Log('v1.4.3', '2021/05/13', [
                    new LogDetail('优化',[
                        '修复了规格图片上传之后无法删除的问题',
                    ]),
                ]),

                new Log('v1.4.2', '2021/05/13', [
                    new LogDetail('页面更新',[
                        '替代了旧的付款方式样本，放上新的付款方式和QR码',
                    ]),
                ]),

                new Log('v1.4.1', '2021/05/12', [
                    new LogDetail('优化',[
                        '优化了商品管理页面的搜索功能',
                        '修复了商品编辑，出现验证错误时，商品英文名字无法正常显示的问题',
                        '修复了订单状态更新界面，订单取消和订单退款倒反的问题',
                    ]),
                ]),

                new Log('v1.4.0', '2021/05/09', [
                    new LogDetail('功能', [
                        '在顾客商品页面添加了商品描述显示',
                        '在顾客商品页面添加了而外商品显示',
                    ]),
                    new LogDetail('优化',[
                        '修复了顾客主页功能条在滑下之后变透明的问题',
                    ]),
                ]),
            ]),

            new LogClass('v1.3', [
                new Log('v1.3.3', '2021/05/08', [
                    new LogDetail('优化',[
                        '修复了商品界面在库存只有1的时候可以添加超过1件商品进购物车的问题',
                        '修复了手机版顾客主页功能建显示不正常的问题',
                        '修复仪表板无法正常显示问题',
                    ]),
                    new LogDetail('页面更新',[
                        '翻译了仪表板页面',
                    ]),
                ]),

                new Log('v1.3.2', '2021/05/07', [
                    new LogDetail('优化',[
                        '移除了商品品牌',
                        '合拼了分开的商品规格名称',
                    ]),
                ]),

                new Log('v1.3.1', '2021/05/06', [
                    new LogDetail('优化',[
                        '修复了外送服务，顾客资料无法保存的问题',
                        '修复了邮费显示不正常问题',
                    ]),
                    new LogDetail('页面更新',[
                        '外送详情更新',
                    ]),
                ]),

                new Log('v1.3.0', '2021/05/05', [
                    new LogDetail('功能', [
                        '添加了商品删除功能',
                        '添加了商品类别管理功能',
                    ]),
                    new LogDetail('优化',[
                        '优化了商品页面图片显示问题',
                    ]),
                    new LogDetail('其他',[
                        '商品样本移除',
                    ]),
                ]),
            ]),

            new LogClass('v1.2', [
                new Log('v1.2.0', '2021/05/02', [
                    new LogDetail('功能', [
                        '添加了商品创建功能',
                        '添加了商品编辑功能',
                    ]),
                    new LogDetail('优化',[
                        '优化了商品库存',
                        '优化了手机页面的商品图片不可在一排显示两个的问题',
                        '优化了商品与订单管理的页面',
                    ]),
                ]),
            ]),

            new LogClass('v1.1', [
                new Log('v1.1.1', '2021/04/25', [
                    new LogDetail('优化',[
                        '修复了订单查看的订单时间不准确的问题'
                    ]),
                ]),
                new Log('v1.1.0', '2021/04/23', [
                    new LogDetail('功能', [
                        '添加了查看订单详情的功能',
                        '添加了订单状态调整的功能',
                    ]),
                    new LogDetail('优化',[
                        '优化了顾客商品页面批发显示的问题'
                    ]),
                ]),
            ]),
        ];

        return view('changingLog.index', compact('currentVersion', 'updateDate', 'color', 'logs'));
    }
}