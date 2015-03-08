迷你同学录 1.1.7 (150308) beta
-------------
[迷你同学录](http://mini_class.piscdong.com/)  
“迷你同学录”是一款基于PHP、MySQL的单班级同学录程序。  
程序完全免费，安装、设置简便，易于使用。  
目前程序还在beta测试阶段，欢迎下载试用，并提出宝贵意见，我们将不断完善程序。  
请勿出售本程序或其修改版，请勿利用本程序或其修改版进行任何商业活动。  
联系信箱：<MiniClass@PiscDong.com>

安装环境要求
-------------
>* PHP 4.1.0 及以上（部分绑定功能需要PHP 5.2及以上）
>* MySQL 3.23 及以上
>* 缩略图功能需要GD 库支持
>* 部分绑定功能需要curl、json等支持

安装过程
-------------
程序解压缩后将所有文件上传到支持PHP、MySQL的服务器空间。  
使用 FTP 软件登录您的服务器，将服务器上安装程序的目录、以及“file/”、“setup/”目录及子目录的属性设置为 777，win 主机请设置 internet 来宾帐户可读写属性。  
上传完毕后，在浏览器中运行程序将进入安装页面，按照提示配置好MySQL信息和同学录信息，安装程序将自动生成配置文件并安装好MySQL数据库。  
安装完毕点击“完成”则进入安装好的同学录首页。

升级历史
-------------
>2015-03-08
>* 1.1.7 (150308)
>* 修正程序错误
>* 升级方法：用新程序替换原有程序

>2013-06-03
>* 1.1.7 (130603)
>* 更新部分功能程序库
>* 恢复检查新版本功能
>* 升级方法：用新程序替换原有程序

>2013-05-17
>* 1.1.7 (130517)
>* 更新部分绑定功能程序库
>* 更新jQuery
>* 暂定检查新版本功能
>* 升级方法：用新程序替换原有程序

>2012-12-31
>* 1.1.7 (121231)
>* 修改绑定新浪微博账号功能
>* 优化程序代码
>* 升级方法：用新程序替换原有程序

>2012-12-12
>* 1.1.7 (121212)
>* 新版本发布
>* 部分其他账号增加refresh_token支持，自动延长绑定时间
>* 修改部分其他账号绑定功能
>* 使用新接口绑定豆瓣账号，旧的API key/私钥以及默认API key/私钥将停止使用，如需使用绑定豆瓣账号功能请使用新接口创建自己专属的应用
>* 使用新接口绑定网易微博，默认Consumer Key/Consumer Secret将停止使用，如需使用绑定网易微博功能请创建自己专属的应用
>* 增加使用Google、Microsoft账号登录功能
>* 增加使用SMTP发送邮件功能
>* 修改Flickr读取数据方法
>* 升级方法：先把数据库升级程序（[update121212.php](https://github.com/piscdong/mini_class/tree/update)）上传到“setup/”，通过浏览器访问：http://yoururl/setup/update121212.php 升级数据库，再用新程序替换原有程序

相关链接
-------------
>[升级程序](https://github.com/piscdong/mini_class/tree/update)  
>[样式下载](https://github.com/piscdong/mini_class/tree/skin)  
>[讨论区](https://github.com/piscdong/mini_class/issues)  
>[升级日志](https://github.com/piscdong/mini_class/wiki/Log)  
>[帮助信息](https://github.com/piscdong/mini_class/wiki/Help)

开发者信息
-------------
[PiscDong studio](http://www.piscdong.com/)  
2015-03-08
