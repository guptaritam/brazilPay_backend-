<!-- Navbar-->
      <header class="main-header hidden-print" style="box-shadow: 0px 0px 10px rgba(0,0,0, .2)">
        <div style="padding: 10px;background-color: #fff" class="mobss"></div>
        <div class="logo"><center><a href="dashboard.php"><img src="Logo.png" style="width: 40px;margin-top:-15px;opacity: .8"></a></center></div>
        <div style="padding: 10px;background-color: #fff;border-bottom: solid 1px #eee" class="mobss"></div>
        <nav class="navbar navbar-static-top nava" style="background-color: #030844;">
          <!-- Sidebar toggle button--><a class="sidebar-toggle" href="#" data-toggle="offcanvas" style="color: #ccc"></a>
          <!-- Navbar Right Menu-->
          <div class="navbar-custom-menu">
            <ul class="top-nav">
              <!--Notification Menu-->
              <li class="dropdown notification-menu" id="mota"><a class="dropdown-toggle" href="notifications.php" data-toggle="dropdown" aria-expanded="false">Notification <i class="fa fa-bell-o fa-lg"></i>

                <?php 
                  try {
                          $stmt = $pdo->prepare('SELECT * FROM `notification` WHERE `for`="admin"  ORDER BY date DESC');
                      } catch(PDOException $ex) {
                          echo "An Error occured!"; 
                          print_r($ex->getMessage());
                      }
                      $stmt->execute();
                      $user = $stmt->fetchAll();                      
                 ?>
                 <?php 
                  if(count($user)!=0){?>
                   <span  class="label label-danger" style="position: absolute;top:10px;right:0px;"><?php echo count($user); ?></span>
                  <?php 
                  }
                  ?>
               </a>
                <ul class="dropdown-menu" style="word-wrap: break-word;width: 340px;">
                 <?php    
                      $i=1;
                      foreach ($user as $key => $value) {
                        if($i<8){
                           echo '<li style="  word-wrap: break-word;"><a style="padding: 20px;  word-wrap: break-word;" class="media" href="notifications.php"><span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary" style="color: #ccc" ></i><i class="fa fa-envelope fa-stack-1x fa-inverse"></i></span></span>
                            <div class="media-body"><span class="block">'.$value['notification'].'</span><span class="text-muted block" style="font-size:12px;">'.$value['date'].'</span></div></a></li>';
                            $i++;
                        }

                      }
                     ?>
                  
                  
                </ul>
              </li>
              <!-- User Menu-->


                <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile &nbsp;&nbsp;<i class="fa  fa-user fa-lg"></i></a>
                <ul class="dropdown-menu settings-menu">
                  <li><a href="change_password.php"><i class="fa fa-user fa-lg"></i>  Profile</a></li>
                  <li><a href="logout.php"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
                </ul>
              </li>
              
            </ul>
          </div>
        </nav>
      </header>
      <!-- Side-Nav-->
      <aside class="main-sidebar hidden-print" style="background-color: #020635;">
        <section class="sidebar">

          <div class="user-panel">
            <div style="padding: 20px;" class="mobss"></div>
            <div class="pull-left image"><img class="" src="Logo.png" alt="User Image" ></div>
            <div class="pull-left info">
              <p>Brazil Pay</p>
              <p class="designation" style="opacity: .4">Super Administrator</p>
            </div>
          </div>
          <!-- Sidebar Menu-->
          <ul class="sidebar-menu">
            <li class="active"><a href="dashboard.php"><i class="fa fa-dashboard"></i><span> Dashboard</span></a></li>
            <li class="treeview"><a href="reposrting.php"><i class="fa fa-laptop"></i><span> Transaction Reporting</span></a></li>
            <li><a href="users.php"><i class="fa fa-user"></i><span> All Users</span></a></li>       
            <li><a href="buy_requests.php"><i class="fa fa-bitcoin"></i><span> Buy Requests</span></a></li>       
            <li><a href="support_requests.php"><i class="fa fa-support"></i><span> Support Requests</span></a></li>       
            <li><a href="notifications.php"><i class="fa fa-bell"></i><span> See all Notifications</span></a></li>  
            <li><a href="kyc.php"><i class="icon-key"></i>&nbsp;&nbsp;&nbsp; <span> KYC Documents</span></a></li>    
            <li><a href="update_price.php"><i class="fa fa-money"></i>&nbsp;&nbsp;&nbsp; <span> Update Price</span></a></li>    
            <li><a href="user_wallet.php"><i class="fa fa-credit-card "></i><span> User Wallet</span></a></li>   
            <li><a href="view_withdraw.php"><i class="icon-cloud-download"></i><span>&nbsp;&nbsp;&nbsp; Withdraw Requests </span></a></li>   
            <li><a href="view_transfers.php"><i class="icon icon-exchange"></i><span>&nbsp;&nbsp;&nbsp; Transfers  </span></a></li>           
          </ul>
        </section>
      </aside>