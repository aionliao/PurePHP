<?php
namespace PurePHP\Mvc;
abstract class Controller extends Mvc{
    protected function View($data = [], $template = '', $debug = false)
    {
        if(is_array($template)){
            $template = '../application/' . $template[0] . '/view/' . $template[1] . '.php';
        }else{
            if($template == '')
                $template = CONTROLLER . '_' . ACTION;
            $template = '../application/' . (MODULE != '' ? MODULE . '/' : '') . 'view/' . $template . '.php';
        }
        if(file_exists($template)) {
            $contents = file_get_contents($template);
            $contents = $this->tp_engine($contents);
            $this->Content($contents, $data, $debug);
        } else {
            throw new Exception('视图文件不存在：' . $template);
        }
    }
    private function tp_engine($c)
    {
        $c = substr($c, 15);
        preg_match_all('/<!--include (.+)-->/Ui', $c, $include);
        foreach ($include[1] as $inc) {

            $inc_array = explode('|', $inc);
            if(isset($inc_array[1])){
                $inc_file = ROOT_PATH . '/application/' . $inc_array[1] . '/view/' . $inc_array[0] . '.php';
            }else{
                $inc_file = VIEW_PATH . $inc_array[0] . '.php';
            }
            $inc_content = file_get_contents($inc_file);
            $inc_content = substr($inc_content, 15);
            $c = str_replace('<!--include ' . $inc . '-->', $inc_content, $c);
        }
        $c = str_replace('<?=', '<?php echo ', $c);
        $c = str_replace('<?', '<?php ', $c);
        $c = str_replace('<?php php', '<?php', $c);
        return $c;
    }
    protected function Content($content, $data=array(), $debug = false)
    {
        $file_name = md5(mb_convert_encoding(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] :
            '', 'UTF-8', 'GBK')) . '_' . time() . rand(1000, 9999);
        $runtime_file = '../runtime/' . $file_name . '.php';
        $of = fopen($runtime_file, 'w+');
        fwrite($of, $content);
        fclose($of);
		if(is_array($data))
            extract($data);
        elseif(is_object($data))
            extract((array)$data);
		else
			$debug = $data;
        include_once ($runtime_file);
        if(!$debug)
            unlink($runtime_file);
    }
    protected function Success($options = []){
        $msg = isset($options['msg']) ? $options['msg'] : '';
        $url = isset($options['url']) ? $options['url'] : '';
        if(isset($options['type'])){
            $type = $options['type'];
        }else{
            if($this->IsAjax())
                $type = 'json';
            else
                $type = 'html';
        }
        if($type == 'json'){
            $this->Json([
                'results' => 'success',
                'msg' => $msg,
                'url' => $url
            ]);
        }else{
            $results = 'success';
            include '../library/PurePHP/Mvc/location.php';
        }
        die();
    }
    protected function Failure($options = []){
        $msg = isset($options['msg']) ? $options['msg'] : '';
        $url = isset($options['url']) ? $options['url'] : '';
        if(isset($options['type'])){
            $type = $options['type'];
        }else{
            if($this->IsAjax())
                $type = 'json';
            else
                $type = 'html';
        }
        if($type == 'json'){
            $this->Json([
                'results' => 'failure',
                'msg' => $msg,
                'url' => $url
            ]);
        }else{
            $results = 'failure';
            include '../library/PurePHP/Mvc/location.php';
        }
        die();
    }
    protected function Json($data){
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    protected function Rdirect($url){
        header('location:' . $url);
    }
    
    function __call($fun, $arg){
        header("HTTP/1.1 404 Not Found");  
        header("Status: 404 Not Found");
        die();
    }
}
