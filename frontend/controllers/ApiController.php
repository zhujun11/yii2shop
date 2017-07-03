<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/29
 * Time: 11:41
 */
namespace frontend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;
use yii\web\UploadedFile;

class ApiController extends Controller{
    //关闭跨域csrf验证
    public $enableCsrfValidation=false;
    //指定数据传输格式json
    public function init()
    {
        \Yii::$app->response->format=Response::FORMAT_JSON;
        parent::init();
    }
    //1.会员
    //会员注册api
    public function actionMemberRegist(){

        $requrest=\Yii::$app->request;
        if ($requrest->isPost){
            $member=new Member();
            $member->scenario=Member::SCENARIO_API_REGISTER;
            $member->username=$requrest->post('username');
            $member->password=$requrest->post('password');
            $member->secondPassword=$requrest->post('secondPassword');
            $member->email=$requrest->post('email');
            $member->tel=$requrest->post('tel');
            $member->catpCode=$requrest->post('catpCode');
            $member->smsCode=$requrest->post('smsCode');

            if ($member->validate()){
                    $member->save(false);
                    return ['success'=>true,'errorMsg'=>'','result'=>$member->toArray()];
            }else{
                return ['success'=>false,'errorMsg'=>$member->getErrors(),'result'=>''];
            }
        }else{
            return ['success'=>false,'errorMsg'=>'请使用post请求方式','result'=>''];
        }
    }
    //会员登录
    public function actionMemberLogin(){
        $requerst=\Yii::$app->request;
        if ($requerst->isPost){
            $username=$requerst->post('username');
            $password=$requerst->post('password');
            $catpCode=$requerst->post('catpCode');
            $remember=0;
            if ($requerst->post('remember')){
            $remember=$requerst->post('remember');
            }
            $user=new LoginForm();
            $user->scenario=LoginForm::SCENARIO_API_LOGIN;
            $user->password=$password;
            $user->username=$username;
            $user->remember=$remember;
            $user->catpCode=$catpCode;
            if ($user->validate()&&$user->loadData()){
                return ['success'=>true,'errorMsg'=>'','result'=>$user->attributes];
            }else{
                return ['success'=>false,'errorMsg'=>$user->getErrors(),'result'=>''];
            }
        }else{
            return ['success'=>false,'errorMsg'=>'请使用post请求方式','result'=>''];
        }
    }
    //修改密码
    public function actionMemberEdit(){
        $user=\Yii::$app->user;
        if ($user->isGuest){
            return ['success'=>false,'errorMsg'=>'请登录','result'=>''];
        }else{
            $request=\Yii::$app->request;
            if ($request->isPost){
                $oldpassword=$request->post('oldPassword');
                $oldUser=Member::findOne($user->id);
                if ($oldUser){
                    $rs=\Yii::$app->security->validatePassword($oldpassword,$oldUser->password_hash);
                    if ($rs){
                        $newpassword=$request->post('newPassword');
                        $secondPassword=$request->post('secondPassword');
                        if ($newpassword){
                            $oldUser->password=$newpassword;
                            $oldUser->secondPassword=$secondPassword;
                            if ($oldUser->save()){

                            return ['success'=>true,'errorMsg'=>'','result'=>$oldUser];
                            }else{
                                return ['success'=>false,'errorMsg'=>$oldUser->getErrors(),'result'=>''];
                            }
                        }else{
                            return ['success'=>false,'errorMsg'=>'新密码不能为空','result'=>''];
                        }
                    }else{
                        return ['success'=>false,'errorMsg'=>'密码错误','result'=>''];
                    }
                }

            }else{
                return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
            }
        }

    }

    //获取当前登录用户信息
    public function actionGetMember(){
       if (\Yii::$app->request->isPost){
           $user=\Yii::$app->user;
           if ($user->isGuest){
               return ['success'=>false,'errorMsg'=>'请登录','result'=>''];
           }else{
               return ['success'=>true,'errorMsg'=>'','result'=>$user->getIdentity()];
           }
       }else{
           return ['success'=>false,'errorMsg'=>'请使用post请求方式获取','result'=>''];
       }
    }

    //2.收货地址
    //添加地址
    public function actionAddressAdd(){
        $user=\Yii::$app->user;
        if ($user->isGuest){
            return ['success'=>false,'errorMsg'=>'请登录','result'=>''];
        }else{
            $request=\Yii::$app->request;
            if ($request->isPost){
                $address=new Address();
                $address->name=$request->post('name');
                $address->member_id=$user->id;
                $address->province_id=$request->post('province_id');
                $address->city_id=$request->post('city_id');
                $address->area_id=$request->post('area_id');
                $address->address=$request->post('address');
                $address->tel=$request->post('tel');
                if ($address->validate() && $address->save()){

                    return ['success'=>true,'errorMsg'=>'','result'=>$address->attributes];
                }else{
                    return ['success'=>false,'errorMsg'=>$address->getErrors(),'result'=>''];
                }
            }else{
                return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
            }
        }
    }


    //修改地址
    public function actionAddressEdit(){
        $user=\Yii::$app->user;
        if ($user->isGuest){
            return ['success'=>false,'errorMsg'=>'请登录','result'=>''];
        }else{
            $request=\Yii::$app->request;
            if ($request->isPost){
                $address_id=$request->post('address_id');
                $address=Address::findOne($address_id);
                $address->name=$request->post('name');
                $address->member_id=$user->id;
                $address->province_id=$request->post('province_id');
                $address->city_id=$request->post('city_id');
                $address->area_id=$request->post('area_id');
                $address->address=$request->post('address');
                $address->tel=$request->post('tel');
                if ($address->validate() && $address->save()){

                    return ['success'=>true,'errorMsg'=>'','result'=>$address->attributes];
                }else{
                    return ['success'=>false,'errorMsg'=>$address->getErrors(),'result'=>''];
                }
            }else{
                return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
            }
        }
    }

    //删除地址
    public function actionAddressDel($address_id){
        $user=\Yii::$app->user;
        if ($user->isGuest){
            return ['success'=>false,'errorMsg'=>'请登录','result'=>''];
        }else{
            $request=\Yii::$app->request;
            if ($request->isGet){

                $address=Address::findOne($address_id);
                if ($address->delete()){

                    return ['success'=>true,'errorMsg'=>'','result'=>$address->attributes];
                }else{
                    return ['success'=>false,'errorMsg'=>$address->getErrors(),'result'=>''];
                }
            }else{
                return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
            }
        }
    }

    //地址列表
    public function actionAddressList($member_id){
        $user=\Yii::$app->user;
        if ($user->isGuest){
            return ['success'=>false,'errorMsg'=>'请登录','result'=>''];
        }else{
            $request=\Yii::$app->request;
            if ($request->isGet){
                if ($member_id){
                    $addresses=Address::findAll(['member_id'=>$member_id]);
                }else{
                    $addresses=Address::findAll(['member_id'=>$user->id]);
                }

                if ($addresses){

                    return ['success'=>true,'errorMsg'=>'','result'=>$addresses];
                }else{
                    return ['success'=>false,'errorMsg'=>'','result'=>''];
                }
            }else{
                return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
            }
        }
    }

    //3.商品分类接口
    //获取所有商品分类
    public function actionGoodsCategory(){
        if (\Yii::$app->request->isGet){
            $goodsCategories=GoodsCategory::find()->all();
            return ['success'=>true,'errorMsg'=>'','result'=>$goodsCategories];
        }else{
            return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
        }
    }
    //获取某分类下面的所有子分类
    public function actionCategory(){
        $request=\Yii::$app->request;
        if ($request->isGet){
            $id=$request->get('id');
            $cate=GoodsCategory::findOne($id);
            $goodsCategories=GoodsCategory::find()->where('lft>'.$cate->lft)->andWhere('rgt<'.$cate->rgt)->andWhere('tree='.$cate->tree)->all();
            if ($goodsCategories){
                return ['success'=>true,'errorMsg'=>'','result'=>$goodsCategories];
            }else{
                return ['success'=>false,'errorMsg'=>'分类不存在','result'=>''];
            }
        }else{
            return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
        }
    }
    //获取某分类的父分类
    public function actionParentCategory(){
        $request=\Yii::$app->request;
        if ($request->isGet){
            $id=$request->get('id');
            $Category=GoodsCategory::findOne($id);
            if ($Category){
                $parentCategory=GoodsCategory::findOne(['id'=>$Category->parent_id]);
                if ($parentCategory){
                    return ['success'=>true,'errorMsg'=>'','result'=>$parentCategory];
                }else{
                    return ['success'=>false,'errorMsg'=>'分类不存在','result'=>''];
                }
            }else{
                return ['success'=>false,'errorMsg'=>'分类不存在','result'=>''];
            }
        }else{
            return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
        }
    }


    //4.商品接口
    //获取某分类下面的所有商品
    public function actionGoodsByCategory(){
        $request=\Yii::$app->request;
        if ($request->isGet){
            $category_id=$request->get('category_id');
            $cate=GoodsCategory::findOne($category_id);
            $query=Goods::find();
            //分页功能
            $pageSize=$request->get('pageSize',2);//每页显示多少条
            $page=$request->get('page',1);//当前第几页
            $page=$page<1?1:$page;//确保不是负页码
            switch ($cate->depth){
                case 2:
                    $query->andWhere(['goods_category_id'=>$category_id])->all();
                    break;
                case 1:
                    $children=$cate->children;
                    $cateIds=ArrayHelper::map($children,'id','id');
                    $query->andWhere(['in','goods_category_id',$cateIds])->all();

                    break;
                case 0:
                    $leaves =GoodsCategory::find()->where(['tree'=>$cate->tree])->leaves()->all();
                    $leaves=ArrayHelper::map($leaves,'id','id');
                    $query->andWhere(['in','goods_category_id',$leaves])->all();

                    break;
            }
            $total=$query->count();
            $goods=$query->offset($pageSize*($page-1))->limit($pageSize)->asArray()->all();
            return ['success'=>true,'errorMsg'=>'','result'=>[
                'pageSize'=>$pageSize,
                'page'=>$page,
                'goodsTotal'=>$total,
                'goodsInfo'=>$goods
            ]];
        }else{
            return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
        }
    }
    //获取某品牌下面的所有商品
    public function actionGoodsByBrand(){
        $request=\Yii::$app->request;
        if ($request->isGet){
            $brand_id=$request->get('brand_id');
            //分页功能
            $pageSize=$request->get('pageSize',2);//每页显示多少条
            $page=$request->get('page',1);//当前第几页
            $page=$page<1?1:$page;//确保不是负页码
            $total=Goods::find()->count();//总页数
            $goods=Goods::find()->where(['brand_id'=>$brand_id])->offset($pageSize*($page-1))->limit($pageSize)->all();
            if ($goods){
                return ['success'=>true,'errorMsg'=>'','result'=>[
                    'pageSize'=>$pageSize,
                    'page'=>$page,
                    'goodsTotal'=>$total,
                    'goodsInfo'=>$goods
                ]];
            }else{
                return ['success'=>false,'errorMsg'=>'该品牌商品不存在','result'=>''];
            }
        }else{
            return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
        }
    }
    //5.文章
    //-获取文章分类
    public function actionArticleCategory(){
        if (\Yii::$app->request->isGet){
            $articleCategories=ArticleCategory::find()->all();
            return ['success'=>true,'errorMsg'=>'','result'=>$articleCategories];
        }else{
            return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
        }
    }
    //-获取某分类下面的所有文章
    public function actionArticleByCategory(){
        $request=\Yii::$app->request;
        if ($request->isGet){
            $category_id=$request->get('category_id');
            $articles=Article::find()->where(['article_category_id'=>$category_id])->all();
            if ($articles){
                return ['success'=>true,'errorMsg'=>'','result'=>$articles];
            }else{
                return ['success'=>false,'errorMsg'=>'文章不存在','result'=>''];
            }
        }else{
            return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
        }
    }
    //-获取某文章所属分类
    public function actionCategoryByArticle(){
        $request=\Yii::$app->request;
        if ($request->isGet){
            $article_id=$request->get('article_id');
            $article=Article::findOne($article_id);
            if ($article){
                $category=ArticleCategory::findOne(['id'=>$article->article_category_id]);
                if ($category){
                    return ['success'=>true,'errorMsg'=>'','result'=>$category];
                }else{
                    return ['success'=>false,'errorMsg'=>'文章分类不存在','result'=>''];
                }
            }else{
                return ['success'=>false,'errorMsg'=>'文章不存在','result'=>''];
            }
        }else{
            return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
        }
    }

    //购物车接口
    //添加商品到购物车
    public function actionCartAdd(){
        $user=\Yii::$app->user;
        $request=\Yii::$app->request;
        if ($request->isPost){//判断是否是post请求
            $goods_id=$request->post('goods_id');
            $amount=$request->post('amount');
            $goods=Goods::findOne($goods_id);
            if ($goods){
                if ($user->isGuest){//判断是否已登录,未登录就保存到cookie
                    $cookies=\Yii::$app->request->cookies;
                    $cookie=$cookies->get('cart');
                    if ($cookie!=null){//如果cookie中已有商品就修改数量
                        $cart=unserialize($cookie->value);
                    }else{//没有就重新创建
                        $cart=[];
                    }
                    $cookies=\Yii::$app->response->cookies;
                    if (key_exists($goods->id,$cart)){
                        $cart[$goods->id]+=$amount;
                    }else{
                        $cart[$goods->id]=$amount;
                    }
                    $cookie = new Cookie([
                       'name'=>'cart',
                        'value'=>serialize($cart),
                    ]);
                    $cookies->add($cookie);
                }else{//登录就保存到数据表
                    //判断数据表中是否存在商品
                    $cart=Cart::findOne(['goods_id'=>$goods->id]);
                    if ($cart==null){
                        $cart=new Cart();
                        $cart->goods_id=$goods->id;
                        $cart->amount=$amount;
                        $cart->member_id=$user->id;
                    }else{
                        $cart->amount+=$amount;
                    }
                    $cart->save();
                }
                return ['success'=>true,'errorMsg'=>'保存成功','result'=>$cart];
            }else{
                return ['success'=>false,'errorMsg'=>'商品不存在','result'=>''];
            }
        }else{
            return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
        }
    }

    //修改购物车某商品数量
    public function actionCartEdit(){
        $request=\Yii::$app->request;
        if ($request->isPost){
            $user=\Yii::$app->user;
            $goods_id=$request->post('goods_id');
            $goods=Goods::findOne($goods_id);
            if ($goods==null){
                return ['success'=>false,'errorMsg'=>'商品不存在','result'=>''];
            }
            $amount=$request->post('amount');
            if ($user->isGuest){//是游客,更改cookie数据
                $cookies=$request->cookies;
                $cookie=$cookies->get('cart');
                if ($cookie==null){
                    $cart=[];
                }else{
                    $cart=unserialize($cookie->value);
                }
                $cookies=\Yii::$app->response->cookies;
                if($amount==0){//删除该商品
                    if (key_exists($goods->id,$cart)) unset($cart[$goods->id]);
                }else{
                    $cart[$goods->id]=$amount;//修改商品数量
                }
                $cookie=new Cookie([
                    'name'=>'cart',
                    'value'=>serialize($cart),
                ]);
                $cookies->add($cookie);
            }else{//已登录更改数据表数据
                $cart_id=$request->post('cart_id');
                $cart=Cart::findOne($cart_id);
                if($cart==null){
                    return ['success'=>false,'errorMsg'=>'购物车数据不存在','result'=>''];
                }else{
                    if ($amount==0){//删除
                        $cart->delete();
                    }else{
                        $cart->amount=$amount;
                        $cart->save();
                    }
                }
            }
            return ['success'=>true,'errorMsg'=>'修改成功','result'=>$cart];
        }else{
            return ['success'=>false,'errorMsg'=>'请求方式错误','result'=>''];
        }
    }

    //清空购物车
    public function actionCartEmpty(){
        if (\Yii::$app->request->isGet){
            $user=\Yii::$app->user;
            if ($user->isGuest){//游客就清空相应cookie
                $cookies=\Yii::$app->request->cookies;
                $cookie=$cookies->get('cart');
                $cookies->remove($cookie);
            }else{//登录就清空用户所有数据记录
                $member_id=$user->id;
                $carts=Cart::find()->where(['member_id'=>$member_id])->all();
                foreach ($carts as $cart){
                    $cart->delete();
                }
            }
            return ['success'=>true,'errorMsg'=>'清空成功','result'=>''];
        }else{
            return ['success'=>false,'errorMsg'=>'打开方式错误','result'=>''];
        }
    }
    //获取购物车所有商品
    public function actionCartGoods(){
        if (\Yii::$app->request->isGet){
        //判断是否是游客
            if (\Yii::$app->user->isGuest){
                //是游客就从cookie中取购物车数据
                $cookies=\Yii::$app->request->cookies;
                $cookie=$cookies->get('cart');
                //判断COOKIE是否存在
                if ($cookie==null){
                    $cart=[];
                }else{
                    $cart=unserialize($cookie->value);
                }
                $goods_info=[];//设置一个数组来保存商品信息
                foreach ($cart as $goods_id =>$amount){
                    //读取数据库中相应商品信息,并转化成数组
                    $goods=Goods::findOne($goods_id)->attributes;
                    $goods['amount']=$amount;
                    $goods_info[]=$goods;
                }

            }else{

                //获取数据库的购物车信息
                $carts=Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
                $goods_info=[];
                if ($carts==null){
                    return ['success'=>false,'errorMsg'=>'购物车没数据','result'=>''];
                }else{
                    foreach ($carts as $cart){
                        $goods=Goods::findOne($cart->goods_id)->attributes;
                        $goods['amount']=$cart->amount;
                        $goods_info[]=$goods;

                    }
                }


            }
            return ['success'=>true,'errorMsg'=>'成功获取','result'=>$goods_info];
        }else{
            return ['success'=>false,'errorMsg'=>'打开方式错误','result'=>''];
        }
    }
    //订单模块接口
    //获取当前用户订单列表
    public function actionOrderList(){
        if (\Yii::$app->request->isGet){
            $user=\Yii::$app->user;
            if ($user->isGuest){//游客就清空相应cookie
                return ['success'=>false,'errorMsg'=>'请登录','result'=>''];
            }else{//登录就清空用户所有数据记录
                $member_id=$user->id;
                $orders=Order::find()->where(['member_id'=>$member_id])->all();
            }
            return ['success'=>true,'errorMsg'=>'获取成功','result'=>$orders];
        }else{
            return ['success'=>false,'errorMsg'=>'打开方式错误','result'=>''];
        }
    }
    //获取支付方式
    public function actionOrderPayment(){
        if (\Yii::$app->request->isGet){
            $user=\Yii::$app->user;
            if ($user->isGuest){
                return ['success'=>false,'errorMsg'=>'请登录','result'=>''];
            }else{
//                $member_id=$user->id;
                $order_id=\Yii::$app->request->get('order_id');
                $order=Order::findOne($order_id);
                if ($order){
                    $payment=[];
                    $payment['payment_id']=$order->payment_id;
                    $payment['payment_name']=$order->payment_name;
                }else{
                    return ['success'=>false,'errorMsg'=>'订单不存在','result'=>''];
                }
            }
            return ['success'=>true,'errorMsg'=>'获取成功','result'=>$payment];
        }else{
            return ['success'=>false,'errorMsg'=>'打开方式错误','result'=>''];
        }
    }
    //获取送货方式
    public function actionOrderDelivery(){
        if (\Yii::$app->request->isGet){
            $user=\Yii::$app->user;
            if ($user->isGuest){
                return ['success'=>false,'errorMsg'=>'请登录','result'=>''];
            }else{
//                $member_id=$user->id;
                $order_id=\Yii::$app->request->get('order_id');
                $order=Order::findOne($order_id);
                if ($order){
                    $delivery=[];
                    $delivery['delivery_id']=$order->delivery_id;
                    $delivery['delivery_name']=$order->delivery_name;
                    $delivery['delivery_price']=$order->delivery_price;
                }else{
                    return ['success'=>false,'errorMsg'=>'订单不存在','result'=>''];
                }
            }
            return ['success'=>true,'errorMsg'=>'获取成功','result'=>$delivery];
        }else{
            return ['success'=>false,'errorMsg'=>'打开方式错误','result'=>''];
        }
    }

    //提交订单
    public function actionOrderCommit(){
        $request=\Yii::$app->request;
        if ($request->isPost){
            $order=new Order();
            $data=$request->post();
            $cart_ids=explode(',',$data['cart_ids']);
            //生成订单
            $order->member_id=\Yii::$app->user->id;
            $address=Address::findOne($data['address_id']);
            $order->name=$address->name;
            $order->province=$address->province->name;
            $order->city=$address->city->name;
            $order->area=$address->area->name;
            $order->address=$address->address;
            $order->tel=$address->tel;
            //送货方式
            $order->delivery_id=$data['delivery_id'];
            $delivery_name='';
            $delivery_price=0;
            foreach (Order::$deliveries as $delivery){
                if ($delivery['id']==$data['delivery_id']){
                    $delivery_name=$delivery['name'];
                    $delivery_price=$delivery['price'];
                }
            }
            $order->delivery_name=$delivery_name;
            $order->delivery_price=$delivery_price;
            //付款方式
            $order->payment_id=$data['payment_id'];
            $payment_name='';
            foreach (Order::$payments as $payment){
                if ($payment['id']==$data['payment_id']){
                    $payment_name=$payment['name'];
                }
            }
            $order->payment_name=$payment_name;
            $order->total=$data['total'];
            $order->status=1;
            $order->create_time=time();
            $transaction=\Yii::$app->db->beginTransaction();//开启事务,在数据写入之前
            try{
                if ($order->save()){

                    //生成订单商品表数据和删除购物车数据
                    foreach ($cart_ids as $cart_id){
                        $order_goods=new OrderGoods();
                        $order_goods->order_id=$order->id;
                        $cart=Cart::findOne($cart_id);
                        $goods=Goods::findOne($cart->goods_id);
                        if ($goods==null){
                            //商品不存在了
                            $goods=new Exception('商品不存在');
                            throw $goods;
                        }
                        if ($cart->amount>$goods->stock){
                            //商品库存不满足购买数量的时候
                            $stock=new Exception('商品已卖完');
                            throw $stock;
                        }

                        $order_goods->goods_id=$cart->goods_id;
                        $order_goods->goods_name=$goods->name;
                        $order_goods->logo=$goods->logo;
                        $order_goods->price=$goods->shop_price;;
                        $order_goods->amount=$cart->amount;
                        $order_goods->status=1;
                        $order_goods->total=$cart->amount*$goods->shop_price;
                        if($order_goods->save()){
                            //修改商品库存
                            $goods->stock-=$cart->amount;
                            $goods->save();
                            //删除购物车数据
                            $cart->delete();
                        }
                    }
                }
                $transaction->commit();
                return ['success'=>true,'errorMsg'=>'提交成功','result'=>$order];
            }catch (Exception $e){
                $transaction->rollBack();
                if ($e==$stock){
                    return ['success'=>false,'errorMsg'=>$order->getErrors(),'result'=>''];
                }
                if ($e==$goods){
                    return ['success'=>false,'errorMsg'=>$order->getErrors(),'result'=>''];
                }
            }
        }else{
            return ['success'=>false,'errorMsg'=>'打开方式错误','result'=>''];
        }

    }

    //取消订单
    public function actionOrderDel(){
        if (\Yii::$app->request->isGet){
            $user=\Yii::$app->user;
            if ($user->isGuest){
                return ['success'=>false,'errorMsg'=>'请登录','result'=>''];
            }else{
//                $member_id=$user->id;
                $order_id=\Yii::$app->request->get('order_id');
                $order=Order::findOne($order_id);
                if($order==null){
                    return ['success'=>false,'errorMsg'=>'订单不存在','result'=>$order];
                }
                if ($order->status==1){
                    $order->status=0;
                    $order->save();
                    return ['success'=>true,'errorMsg'=>'取消成功','result'=>$order];
                }else{
                    return ['success'=>false,'errorMsg'=>'订单已付费或已取消','result'=>''];
                }
            }

        }else{
            return ['success'=>false,'errorMsg'=>'打开方式错误','result'=>''];
        }
    }


    //测试
    //验证码
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }

    //文件上传
    public function actionFileUp(){
        if (\Yii::$app->request->isPost){
        $img=UploadedFile::getInstanceByName('img');
            if ($img){
                $fileName='upload/'.uniqid().'.'.$img->extension;
                $rs=$img->saveAs(\Yii::getAlias('@webroot/').$fileName,false);
                if ($rs){
                    return ['success'=>false,'errorMsg'=>'文件上传成功','result'=>$fileName];
                }else{
                    return ['success'=>false,'errorMsg'=>'文件上传失败','result'=>$img->error];
                }
            }else{
                return ['success'=>false,'errorMsg'=>'文件上传失败','result'=>$img->error];
            }

        }else{
            return ['success'=>false,'errorMsg'=>'打开方式错误','result'=>''];
        }
    }

    //手机验证码
    public function actionSms(){
        $request=\Yii::$app->request;
        if ($request->isPost) {
            //接收前台传过来的电话号码
            $tel = $request->post('tel');
            if (!preg_match('/^1[34578]\d{9}$/', $tel)) {
                return ['success' => false, 'errorMsg' => '手机号错误', 'result' => ''];
            }
            //判断同号码发送时间间隔
            $timeValue = \Yii::$app->cache->get('time_tel_' . $tel);
            $time = time() - $timeValue;//间隔时间
            if ($time < 60) {
                return ['success' => false, 'errorMsg' => '请' . (60 - $time) . '秒后再试', 'result' => ''];
            }
            //生成随机验证码
            $code = rand(100000, 999999);
//        var_dump($code);exit;
            //调用短信发送组件
            //$rs=\Yii::$app->sms->setNum($tel)->setParam(['code'=>$code])->send();
            if ($rs = 1) {
                //使用缓存(redis),session,mysql保存手机号好随机码
                \Yii::$app->cache->set('tel_' . $tel, $code, 5 * 60);
                //用来验证同号码间隔时间
                \Yii::$app->cache->set('time_tel_' . $tel, time(), 5 * 60);
                return ['success' => true, 'errorMsg' => '发送成功', 'result' => [
                    'tel'=>$tel,
                    'code'=>$code
                ]];
            } else {
                return ['success' => false, 'errorMsg' => '发送失败', 'result' => ''];
            }
//        var_dump($tel);exit;
        }else{
            return ['success'=>false,'errorMsg'=>'打开方式错误','result'=>''];
        }
    }
}