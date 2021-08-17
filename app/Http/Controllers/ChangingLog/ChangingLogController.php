<?php

namespace App\Http\Controllers\ChangingLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangingLogController extends Controller
{
    public function index()
    {

        $currentVersion = 'v2.2.2 公测版';
        $updateDate = '2021/08/17';
        $color = 'orange'; // Beta: orange, Release: green


        $logs = [

            new LogClass('v2.2', [

                new Log('v2.2.2', '2021/08/17', [

                    new LogDetail('优化', [
                        '修复了订单日期选择无法搜索指定日期的订单的问题',
                        '修复了照片上传失败后却可以保存的问题',
                    ]),
                ]),

                new Log('v2.2.1', '2021/08/16', [

                    new LogDetail('优化', [
                        '修复了折扣没有跟着设定的时间的问题',
                    ]),
                ]),

                new Log('v2.2.0', '2021/08/16', [

                    new LogDetail('功能', [
                        '使用了全新商品编辑界面',
                        '使用了新的外观',
                        '新增了商品折扣功能',
                    ]),

                    new LogDetail('优化', [
                        '优化了各大页面',
                        '优化了商品照片储存方式',
                        '优化了商品照片显示方式',
                        '优化了图片上传出现错误的问题',
                        '优化了网页加载速度',
                    ]),

                    new LogDetail('其他', [
                        '移除了一些残留的资源',
                    ]),
                ]),

            ]),

            new LogClass('v2.1', [

                new Log('v2.1.1', '2021/07/07', [
                    new LogDetail('优化', [
                        '修复了商品价钱显示的问题',
                    ]),
                ]),

                new Log('v2.1.0', '2021/06/27', [
                    new LogDetail('功能', [
                        '添加了多样商品加入购物车的功能',
                    ]),

                    new LogDetail('优化', [
                        '优化了商品浏览页面',
                        '优化了商品页面',
                        '优化了购物车页面',
                        '优化了下单成功页面',
                    ]),

                    new LogDetail('其他', [
                        '移除了一些残留的资源',
                    ]),
                ]),

            ]),

            new LogClass('v2.0', [

                new Log('v2.0.3', '2021/06/24', [
                    new LogDetail('优化',[
                        '修复了商品名称可以重复的问题',
                        '优化了顾客页面浏览商品页面链接，名字可允许放置"/"符号',
                    ]),
                ]),

                new Log('v2.0.2', '2021/06/14', [
                    new LogDetail('优化',[
                        '修复了相同商品类别列表显示超过10样商品的问题（顾客商品页面）',
                        '修复了购物车部分图标显示不正常的问题',
                        '修复了在英文页面，只有一张商品照片时，显示不正常的问题',
                        '优化了加入购物车讯息显示代码',
                        '商品管理主页现在可以查看商品封面',
                        '优化了商品编辑界面',
                        '优化了账号管理，特权相关功能'
                    ]),

                    new LogDetail('页面更新', [
                        '主页与所有商品列表页面结合',
                        '关于页面移除',
                    ]),
                ]),

                new Log('v2.0.1', '2021/06/14', [
                    new LogDetail('优化',[
                        '添加回之前误删的订单顾客显示的问题',
                    ]),
                ]),

                new Log('v2.0.0', '2021/06/12', [
                    new LogDetail('功能', [
                        '添加了账号权限功能，并可供给管理员管理',
                        '添加了个人账号设置功能',
                        '添加了订单开头于金宝邮费设置',
                        '添加了商品上下架按钮功能',
                        '添加了已退款的计算（仪表板）',
                        '添加了可自选一页显示多少项目的功能（管理员后台）',
                    ]),

                    new LogDetail('优化', [
                        '优化了商品类别管理页面',
                        '优化了仪表板手机页面等问题',
                        '优化了商品类别标签功能',
                        '修复了规格照片下方规格名称超出窗口的问题（商品编辑）',
                        '优化了商品查看界面',
                        '优化了订单查看界面',
                        '优化了商品编辑界面',
                        '翻译了错误显示的语言',
                    ]),

                    new LogDetail('页面更新', [
                        '客服功能更换去WhatsApp（顾客页面）',
                    ]),
                ]),
            ]),

            new LogClass('v1.5', [

                new Log('v1.5.4', '2021/05/29', [
                    new LogDetail('优化',[
                        '修复了同商品类别的商品列表显示不正常的问题（顾客商品页面）',
                    ]),
                ]),

                new Log('v1.5.3', '2021/05/25', [
                    new LogDetail('优化',[
                        '优化了商品描述字体超出窗口范围的问题',
                    ]),
                ]),

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
