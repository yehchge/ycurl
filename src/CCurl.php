<?php declare(strict_types=1);

namespace yehchge\ycurl;

class CCurl {

    public function __construct(){
    }

    /**
     * 取得網頁內容
     * @param  string $method    GET or POST or PUT
     * @param  string $url       網址
     * @param  array  $data      資料陣列, 包含認證資訊
     * @param  string $head_type 取得 wordpress header 內的文章總數
     * @return json              json 資料
     * @created 2023/06/09
     */
    public function fetch($method, $url, $data = array(), $head_type = ''){
        $headers = [];

        $ch = curl_init();

        $method = strtoupper($method);

        foreach($data as $key => $val){
            if($key=='auth'){
                $user = $val[0];
                $pass = $val[1];
                curl_setopt($ch, CURLOPT_USERPWD, $user.':'.$pass);
            }
        }

        if(isset($data['auth'])) unset($data['auth']);

        $body = isset($data['body'])?$data['body']:'';

        switch($method){
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if($body){
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                }
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if($body){
                    curl_SETOPT($ch, CURLOPT_POSTFIELDS, $body);
                }
                break;
            case 'GET':
            default:
                if($body){
                    $url = sprintf("%s?%s", $url, http_build_query($body));
                }
                break;
        }

        // OPTIONS:
        curl_setopt($ch, CURLOPT_URL, trim($url));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        # The request will fail without including options for dealing with SSL.
        if(parse_url($url, PHP_URL_SCHEME)=='https'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        }

        if($head_type=='wp'){
            $callback = function($ch, $header) use (&$headers){
                
                $len = strlen( $header );
                $header = explode(':', $header, 2 );
                if( count( $header ) < 2 ) return $len;
                
                $param = strtolower( trim( $header[0] ) );
                $value = trim( $header[1] );
                
                if( strstr( $param, 'x-wp' ) ) $headers[ $param ] = $value;
                return $len;
            };

            # we do not need the header in the output
            curl_setopt($ch, CURLOPT_HEADER, false);

            # but we do need to process request headers - find any X-WP* headers
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, $callback);
        }

        // EXECUTE:
        $res = curl_exec($ch);

        curl_close($ch);

        # process the required headers... 
        // printf( '<pre>%s</pre>', print_r( $headers, true ) );

        if($head_type=='wp'){
            if(!$headers || !is_array($headers)){
                die('get page error, try agein.');
            }

            return json_encode($headers);
        }

        if(!$res) {
            die("Connection Failure");
        }

        return $res;
    }

}
