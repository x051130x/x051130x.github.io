  1 <?php
  2  
  3     /**
  4     * Minecraft服务器状态查询
  5     * @作者 Julian Spravil <julian.spr@t-online.de> Git地址：https://github.com/FunnyItsElmo
  6     * @本库免费使用，但不要删除作者和版权。  8     */
  9     class MinecraftServerStatus {
 10  
 11         private $timeout;
 12  
 13         public function __construct($timeout = 2) {
 14             $this->timeout = $timeout;
 15         }
 16  
 17         public function getStatus($host = '127.0.0.1', $version = '1.7.*' , $port = 25565) {
 18  
 19             if (substr_count($host , '.') != 4) $host = gethostbyname($host);
 20  
 21             $serverdata = array();
 22             $serverdata['hostname'] = $host;
 23             $serverdata['version'] = false;
 24             $serverdata['protocol'] = false;
 25             $serverdata['players'] = false;
 26             $serverdata['maxplayers'] = false;
 27             $serverdata['motd'] = false;
 28             $serverdata['motd_raw'] = false;
 29             $serverdata['favicon'] = false;
 30             $serverdata['ping'] = false;
 31  
 32             $socket = $this->connect($host, $port);
 33  
 34             if(!$socket) {
 35                 return false;
 36             }
 37  
 38             if(preg_match('/1.7|1.8/',$version)) {
 39  
 40                 $start = microtime(true);
 41  
 42                 $handshake = pack('cccca*', hexdec(strlen($host)), 0, 0x04, strlen($host), $host).pack('nc', $port, 0x01);
 43  
 44                 socket_send($socket, $handshake, strlen($handshake), 0); //give the server a high five
 45                 socket_send($socket, "\x01\x00", 2, 0);
 46                 socket_read( $socket, 1 );
 47  
 48                 $ping = round((microtime(true)-$start)*1000); //calculate the high five duration
 49  
 50                 $packetlength = $this->read_packet_length($socket);
 51  
 52                 if($packetlength < 10) {
 53                     return false;
 54                 }
 55  
 56                 socket_read($socket, 1);
 57  
 58                 $packetlength = $this->read_packet_length($socket);
 59  
 60                 $data = socket_read($socket, $packetlength, PHP_NORMAL_READ);
 61  
 62                 if(!$data) {
 63                     return false;
 64                 }
 65  
 66                 $data = json_decode($data);
 67  
 68                 $serverdata['version'] = $data->version->name;
 69                 $serverdata['protocol'] = $data->version->protocol;
 70                 $serverdata['players'] = $data->players->online;
 71                 $serverdata['maxplayers'] = $data->players->max;
 72  
 73                 $motd = $data->description;
 74                 $motd = preg_replace("/(§.)/", "",$motd);
 75                 $motd = preg_replace("/[^[:alnum:][:punct:] ]/", "", $motd);
 76  
 77                 $serverdata['motd'] = $motd;
 78                 $serverdata['motd_raw'] = $data->description;
 79                 $serverdata['favicon'] = $data->favicon;
 80                 $serverdata['ping'] = $ping;
 81  
 82             } else {
 83  
 84                 $start = microtime(true);
 85  
 86                 socket_send($socket, "\xFE\x01", 2, 0);
 87                 $length = socket_recv($socket, $data, 512, 0);
 88  
 89                 $ping = round((microtime(true)-$start)*1000);//calculate the high five duration
 90          
 91                 if($length < 4 || $data[0] != "\xFF") {
 92                     return false;
 93                 }
 94  
 95                 $motd = "";
 96                 $motdraw = "";
 97  
 98                 //Evaluate the received data
 99                 if (substr((String)$data, 3, 5) == "\x00\xa7\x00\x31\x00"){
100  
101                     $result = explode("\x00", mb_convert_encoding(substr((String)$data, 15), 'UTF-8', 'UCS-2'));
102                     $motd = $result[1];
103                     $motdraw = $motd;
104  
105                 } else {
106  
107                     $result = explode('§', mb_convert_encoding(substr((String)$data, 3), 'UTF-8', 'UCS-2'));
108                         foreach ($result as $key => $string) {
109                             if($key != sizeof($result)-1 && $key != sizeof($result)-2 && $key != 0) {
110                                 $motd .= '§'.$string;
111                             }
112                         }
113                         $motdraw = $motd;
114                     }
115  
116                     $motd = preg_replace("/(§.)/", "", $motd);
117                     $motd = preg_replace("/[^[:alnum:][:punct:] ]/", "", $motd); //Remove all special characters from a string
118  
119                     $serverdata['version'] = $result[0];
120                     $serverdata['players'] = $result[sizeof($result)-2];
121                     $serverdata['maxplayers'] = $result[sizeof($result)-1];
122                     $serverdata['motd'] = $motd;
123                     $serverdata['motd_raw'] = $motdraw;
124                     $serverdata['ping'] = $ping;
125  
126             }
127  
128             $this->disconnect($socket);
129  
130             return $serverdata;
131  
132         }
133  
134         private function connect($host, $port) {
135             $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
136             socket_connect($socket, $host, $port);
137             return $socket;
138         }
139  
140         private function disconnect($socket) {
141             if($socket != null) {
142                 socket_close($socket);
143             }
144         }
145  
146         private function read_packet_length($socket) {
147             $a = 0;
148             $b = 0;
149             while(true) {
150                 $c = socket_read($socket, 1);
151                 if(!$c) {
152                     return 0;
153                 }
154                 $c = Ord($c);
155                 $a |= ($c & 0x7F) << $b++ * 7;
156                 if( $b > 5 ) {
157                     return false;
158                 }
159                 if(($c & 0x80) != 128) {
160                     break;
161                 }
162             }
163             return $a;
164         }
165  
166     }
167 ?>
