<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
        <a href="#">
            <img height="50" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/image/cf.png">
        </a>
        </div>          
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav">
                <li><a href="<?php echo Yii::app()->createUrl("supplier/index"); ?>">首頁</a></li>
                <li><a href="<?php echo Yii::app()->createUrl("supplier/mySite"); ?>">我的網站</a></li>
                <li><a href="<?php echo Yii::app()->createUrl("supplier/report"); ?>">報表查詢</a></li>
                <li><a href="<?php echo Yii::app()->createUrl("supplier/payments"); ?>">付費</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span><span><?php echo Yii::app()->user->name;?>(<?php echo $supplier->name;?>)</span><span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <?php if($this->user->group != 7){ ?>
                            <li><a href="<?php echo Yii::app()->createUrl("supplier/downloadContract"); ?>"> 下載合約</a></li> 
                            <li><a href="/"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> 回後台</a></li>
                        <?php }else{ ?>
                            <li><a href="repassword">修改密碼</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo Yii::app()->createUrl("supplier/downloadContract"); ?>">下載合約</a></li>
                            <li><a href="<?php echo Yii::app()->createUrl("login/out"); ?>"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> 登出</a></li>
                        <?php }?>
                    </ul>
                </li>
<!--                 <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">設定(未開放)</a></li>
                        <li><a href="payments">付費</a></li>
                    </ul>
                </li> -->
                <li id="message-box">
                    <?php
                        if(Yii::app()->user->id > 0){
                            $this->widget('SupplierMessageWidget');
                        }
                    ?>
                </li>
            </ul>
        </div>              
    </div>  
</nav>