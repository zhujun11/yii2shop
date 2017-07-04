<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/7/3
 * Time: 18:53
 * 消息回复，回复普通文本消息，回复多图文消息
 * 设置菜单 （view click）
 * 处理菜单点击事件（点击菜单回复文本信息，点击菜单回复图文信息）
 * 网页授权获取openid
 * 绑定账户
 * 获取用户的订单
 */
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use EasyWeChat\Message\News;
use frontend\models\Address;
use frontend\models\Member;
use frontend\models\Order;
use yii\helpers\Url;
use yii\web\Controller;
use EasyWeChat\Foundation\Application;

class WechatController extends Controller{
    //关闭跨站攻击验证
    public $enableCsrfValidation=false;
    function actionIndex(){
        $app = new Application(\Yii::$app->params['wechat']);

        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            switch ($message->MsgType) {
                case 'text':
                    switch ($message->Content) {
                        case '活动'://回复普通文本消息，回复多图文消息
                            $news1 = new News([
                                'title' => '千年咸鱼大甩卖',
                                'description' => '千年咸鱼大甩卖',
                                'url' => 'http://baidu.com',
                                'image' => 'http://img1.gamersky.com/image2014/05/20140530yx_3/gamersky_103small_206_201453015114B3.jpg',
                                // ...
                            ]);
                            $news2 = new News([
                                'title' => '千年咸鸭蛋大甩卖',
                                'description' => '千年咸鸭蛋大甩卖',
                                'url' => 'https://www.sogou.com/',
                                'image' => 'http://i01.pic.sogou.com/5f3eb5f75a5e24a4',
                                // ...
                            ]);
                            $news3 = new News([
                                'title' => '千年粽子大甩卖',
                                'description' => '千年粽子大甩卖',
                                'url' => 'http://www.itsource.cn/',
                                'image' => 'http://shipin.people.com.cn/NMediaFile/2014/0522/MAIN201405221352000538483682325.jpg',
                                // ...
                            ]);
// or
                            return [$news1,$news2,$news3];

                    }

                    return '收到您发送的消息是:' . $message->Content;
                    break;
                case 'event':
                    //事件的类型   $message->Event
                    //事件的key值  $message->EventKey
                    switch ($message->Event) {
                        case 'CLICK':
                            switch ($message->EventKey) {
                                case 'cxsp'://点击促销商品
                                    //获取促销商品---最新吧
                                    $allGoods = Goods::find()->orderBy(['id'=>SORT_DESC])->limit(5)->all();
                                    $allNews=[];
                                    foreach ($allGoods as $k=> $goods){
                                    $allNews[$k] = new News([
                                        'title' => $goods->name,
                                        'description' => strip_tags($goods->intro->content),
                                        'url' => Url::to(['wechat/goods','id'=>$goods->id],true),
                                        'image' => 'http://home.zhjun520.top'.$goods->logo
                                        // ...
                                    ]);

                                    }
//                                    var_dump($allNews);
                                    return $allNews;


                                    break;
                            }

                        break;
                    }
                return '事件类型是:' . $message->Event . '事件的key值是:' . $message->EventKey;
                break;


                case 'view':
                    # code...
                    break;


                case 'image':
                    return '收到图片消息';
                    break;
            }

        });

        // 将响应输出
        $response = $app->server->serve();

        $response->send(); // Laravel 里请使用：return $response;
    }
    //设置菜单
    public function actionSetMenu(){
        $app = new Application(\Yii::$app->params['wechat']);
        $menu = $app->menu;
        $buttons = [
            [
                "type" => "click",
                "name" => "促销商品",
                "key"  => "cxsp"
            ],
            [
                "type" => "view",
                "name" => "在线商城",
                "url"  => Url::to(['wechat/home'],true),
            ],
            [
                "name"       => "个人中心",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "收货地址",
                        "url"  => Url::to(['wechat/address'],true),
                    ],
                    [
                        "type" => "view",
                        "name" => "修改密码",
                        "url"  => Url::to(['wechat/password'],true),
                    ],
                    [
                        "type" => "view",
                        "name" => "我的订单",
                        "url"  => Url::to(['wechat/order'],true),
                    ],
                    [
                        "type" => "view",
                        "name" => "绑定账号",
                        "url" => Url::to(['wechat/login'],true),
                    ],
                ],
            ],
        ];
        $menu->add($buttons);
        $menus = $menu->all();
        var_dump($menus);
    }

    //网页授权---设置微信的openid到session中
    public function actionAddress(){
        //先确认session中是否存在openID
        $openid=\Yii::$app->session->get('openid');
        if ($openid==null){
            //发起授权
            //保存路由到session
            $url=\Yii::$app->controller->action->uniqueId;
            \Yii::$app->session->set('redirect',$url);
            $app=new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }
        $member=Member::findOne(['openid'=>$openid]);
        //验证是否是绑定用户
        if ($member){
            $allAddress=Address::findAll(['member_id'=>$member->id]);
            return $this->renderPartial('address',['allAddress'=>$allAddress]);
        }else{//不是就跳转绑定页面
            return $this->redirect(['wechat/login']);
        }


    }
    //授权回调页面
    public function actionCallback(){
        $app = new Application(\Yii::$app->params['wechat']);
        $user = $app->oauth->user();
        // $user 可以用的方法:
        // $user->getId();  // 对应微信的 OPENID
        // $user->getNickname(); // 对应微信的 nickname
        // $user->getName(); // 对应微信的 nickname
        // $user->getAvatar(); // 头像网址
        // $user->getOriginal(); // 原始API返回的结果
        // $user->getToken(); // access_token， 比如用于地址共享时使用
        //将OPENID保存到session中
        \Yii::$app->session->set('openid',$user->getId());
        //跳回原网页
        return $this->redirect([\Yii::$app->session->get('redirect')]);
    }
    //我的订单
    public function actionOrder(){
        //先确认session中是否存在openID
        $openid=\Yii::$app->session->get('openid');
        if ($openid==null){
            //发起授权
            //保存路由到session
            $url=\Yii::$app->controller->action->uniqueId;
            \Yii::$app->session->set('redirect',$url);
            $app=new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }
        $member=Member::findOne(['openid'=>$openid]);
        //验证是否是绑定用户
        if ($member){
            $orders=Order::findAll(['member_id'=>$member->id]);
            return $this->renderPartial('order',['orders'=>$orders]);
        }else{//不是就跳转绑定页面
            return $this->redirect(['wechat/login']);
        }
    }
    //绑定账号---将openid和用户账号绑定
    public function actionLogin(){
        //先查看是否在session中存在openID
        $openid=\Yii::$app->session->get('openid');
        if ($openid==null){//没有就去获取---网页授权
            //发起授权
            //保存路由到session
            $url=\Yii::$app->controller->action->uniqueId;
            \Yii::$app->session->set('redirect',$url);
            $app=new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }
        //再让用户登录
        $request=\Yii::$app->request;
        if ($request->isPost){
            $username=$request->post('username');
            $password=$request->post('password');
            $member=Member::findOne(['username'=>$username]);
            if ($member && \Yii::$app->security->validatePassword($password,$member->password_hash)){
                //登录成功更新openID字段
                Member::updateAll(['openid'=>$openid],'id='.$member->id);
                if ($redirect=\Yii::$app->session->get('redirect')){
                    return $this->redirect([$redirect]);
                }
                echo '登录成功';exit;
            }else{
                echo '登录失败';exit;
            }
        }

        return $this->renderPartial('login');
    }

    public function actionRemove(){
        \Yii::$app->session->removeAll();
    }

    public function actionTest(){
        $allGoods=Goods::find()->orderBy(['id'=>SORT_DESC])->limit(5)->all();
        var_dump($allGoods);
    }

    //商品详情页
    public function actionGoods(){
        $id=\Yii::$app->request->get('id');
        $goods=\backend\models\Goods::findOne($id);
        return $this->renderPartial('goods',['goods'=>$goods]);
    }
    //商城首页
    public function actionHome(){
        $goodsCategories=GoodsCategory::find()->where(['parent_id'=>0])->all();

        return $this->render('home',['goodsCategories'=>$goodsCategories]);
    }
    //修改密码
    public function actionPassword(){
        //先确认session中是否存在openID
        $openid=\Yii::$app->session->get('openid');
        if ($openid==null){
            //发起授权
            //保存路由到session
            $url=\Yii::$app->controller->action->uniqueId;
            \Yii::$app->session->set('redirect',$url);
            $app=new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }
        $member=Member::findOne(['openid'=>$openid]);//判断是否已绑定
        //验证是否是绑定用户
        if ($member){
            $request=\Yii::$app->request;
            if ($request->isPost){
                $oldPassword=$request->post('old_password');
                $newPassword=$request->post('new_password');
                $secondPassword=$request->post('second_password');
                //验证用户密码是否正确
                $rs=\Yii::$app->security->validatePassword($oldPassword,$member->password_hash);
                if ($rs){
                    if ($newPassword==$secondPassword){
                        Member::updateAll(['password_hash'=>\Yii::$app->security->generatePasswordHash($newPassword)],'id='.$member->id);
                        echo '修改成功';
                    }else{
                        echo '两次密码不一致';
                        return $this->redirect(['wechat/password','ref' => 3]);
                    }
                }else{
                    echo '账号或密码错误';
                    return $this->redirect(['wechat/password','ref' => 3]);
                }
            }
            return $this->renderPartial('password');
        }else{//不是就跳转绑定页面
            return $this->redirect(['wechat/login']);
        }
    }
}