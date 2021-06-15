<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2021-06-08 17:56:18
 * @version $Id$
 */
namespace app\wahh\Controller;
use think\DB;
use think\Controller;
use think\Request;
use think\Session;
use think\Cookie;
class Register extends Controller{
    
    public function register(){
        if(request()->isPost()){ 
            //tp5下封装的，获取post传过来的值 input（'post.name'）
            $response =[];
                // 获取前段post过来的数据
            $data = input('post.');
//            {admin_name: "admin", password: "123456"}
            $username = $data['uname'];
            $tp_upwd = md5($data['upwd']);
            $tp_upwd2 = md5($data['upwd2']);
                // 判断账号密码是否为空，不为空就遍历一遍数据库的 账号 列，查询是否有相同账号——有：就提示重新填写，无：
            if ($tp_upwd != ' ' && $tp_upwd2 != ' ' && $username != ' ') {
            $where['tp_uname'] = $username;
            $res = Db::name('user')->where($where)->find();
           // 判断密码是否相同 /将数据插入数据库、session、cookie等
            if( $res && $tp_upwd === $tp_upwd2 ){
                $InsertRes=Db::table('user')->insert($res);
               switch ( $InsertRes ) {
                   case 1:
                        Cookie('uid',$res['id']);
                        Cookie('uname',$res['tp_uname']);
                        Session('uid',$res['id']);
                        Session('uname',$res['tp_uname']);
                        $response['status'] = 1;
                        $response['info'] = '注册成功';
                        return $response;
                       break;
                   
                   case 0:
                        $response['status'] = 0;
                        $response['info'] = '注册失败';
                        return $response;
                       break;
                    }
               
               
           
         
                $this->assign(['username' => $username]);
                return $this->fetch('index/index');
            }
        } 
    }
            return view();

    }
}