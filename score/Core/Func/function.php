<?php
/**
+----------------------------------------------------------------------
| swoolefy framework bases on swoole extension development, we can use it easily!
+----------------------------------------------------------------------
| Licensed ( https://opensource.org/licenses/MIT )
+----------------------------------------------------------------------
| Author: bingcool <bingcoolhuang@gmail.com || 2437667702@qq.com>
+----------------------------------------------------------------------
*/

use Swoolefy\Core\Application;
/**
 * dump，调试函数
 * @param    $var
 * @param    $echo
 * @param    $label
 * @param    $strict
 * @return   string            
 */
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES, 'UTF-8') . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        // 获取终端输出
        $output = ob_get_clean();
        if(!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES, 'UTF-8') . '</pre>';
        }
    }
    if($echo) {
        // 调试环境这个函数使用
        if(!IS_PRD_ENV()) {
            $app = Application::getApp();
            if(is_object($app)) {
                $app->response->header('Content-Type','text/html; charset=utf-8');
                // worker启动时打印的信息，在下一次请求到来时打印出来
                if(!empty($output)) {
                    $app->response->write($output);
                }
            }  
        }
        return null;
    }else {
        return $output;
    }
        
}

/**
 * _die 异常终端程序执行
 * @param    $msg
 * @param    $code
 * @throws   \Exception
 * @return   mixed
 */
function _die($msg = '', int $code = 1) {
    throw new \Exception($msg, $code);
}

/**
 * _each
 * @param $msg
 * @param string $foreground
 * @param string $background
 */
function _each(string $msg, string $foreground = "red", string $background = "black") {
    // Create new Colors class
    static $colors;
    if(!isset($colors)) {
        $colors = new \Swoolefy\Tool\EachColor();
    }
    echo $colors->getColoredString($msg, $foreground, $background);
}
