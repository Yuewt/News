<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 登陆按钮调用
 */
class LoginController extends Controller {

    public function index(){
        if(session('adminUser')) { //当这个session存在的时候，跳转到后台首页
           $this->redirect('/admin.php?c=index');
        }
        // admin.php?c=index
        $this->display();
    }

    public function check() {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if(!trim($username)) {
            return show(0,'用户名不能为空');
        }
        if(!trim($password)) {
            return show(0,'密码不能为空');
        }

        $ret = D('Admin')->getAdminByUsername($username); //调用数据库
        if(!$ret || $ret['status'] !=1) {
            return show(0,'该用户不存在');
        }

        if($ret['password'] != getMd5Password($password)) {
            return show(0,'密码错误');
        }

        D("Admin")->updateByAdminId($ret['admin_id'],array('lastlogintime'=>time()));

        session('adminUser', $ret); //放入session中
        return show(1,'登录成功');


    }

    public function loginout() {
        session('adminUser', null);
        $this->redirect('/admin.php?c=login');
    }

}