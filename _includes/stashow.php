 <?php
    include_once 'status.php';
    
    $status = new MinecraftServerStatus(); // 类
    $response = $status-> getStatus('45.125.46.48', 44991); // 服务器地址

if(!$response) {
    echo"OFFLINE";
} else {
    echo"".$response['players']."";
}
 
?> 