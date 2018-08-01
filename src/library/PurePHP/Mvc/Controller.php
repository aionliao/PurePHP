<?php

namespace PurePHP\Mvc;

use PurePHP\Http\Request;

abstract class Controller extends Mvc
{
    protected function view($data = [], $template = '', $debug = false)
    {
        if (is_array($template)) {
            $template = ROOT_PATH . '/application/' . $template[0] . '/View/' . $template[1] . '.php';
        } else {
            if ($template == '') {
                $template = CONTROLLER . '_' . ACTION;
            }
            $template = ROOT_PATH . '/application/' . (MODULE != '' ? MODULE . '/' : '') . 'View/' . $template . '.php';
        }
        if (file_exists($template)) {
            $contents = file_get_contents($template);
            $contents = $this->tp_engine($contents);
            $this->show($contents, $data, $debug);
        } else {
            die('视图文件不存在：' . $template);
        }
    }
    private function tp_engine($c)
    {
        preg_match_all('/<!--include (.+)-->/Ui', $c, $include);
        foreach ($include[1] as $inc) {
            $inc_array = explode('|', $inc);
            if (isset($inc_array[1])) {
                $inc_file = ROOT_PATH . '/application/' . $inc_array[1] . '/View/' . $inc_array[0] . '.php';
            } else {
                $inc_file = ROOT_PATH . '/application/' . (MODULE != '' ? MODULE . '/' : '') . '/View/' . $inc_array[0] . '.php';
            }
            $inc_content = file_get_contents($inc_file);
            $c = str_replace('<!--include ' . $inc . '-->', $inc_content, $c);
        }
        $c = str_replace('<?=', '<?php echo ', $c);
        $c = str_replace('<?', '<?php ', $c);
        $c = str_replace('<?php php', '<?php', $c);
        return $c;
    }
    protected function show($content, $data = [], $debug = false)
    {
        $file_name = md5(mb_convert_encoding(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '', 'UTF-8', 'GBK')) . '_' . time() . rand(1000, 9999);
        $runtime_file = '../runtime/' . $file_name . '.php';
        $of = fopen($runtime_file, 'w+');
        fwrite($of, $content);
        fclose($of);
		if (is_array($data)) {
            extract($data);
        } elseif(is_object($data)) {
            extract((array)$data);
        } else {
            $debug = $data;
        }
        header('Content-Type:text/html; charset=utf-8');
        include_once ($runtime_file);
        if (!$debug) {
            unlink($runtime_file);
        }
    }
    public function content($content)
    {
        header('Content-Type:text/plain; charset=utf-8');
        echo $content;
    }
    protected function success($options = [])
    {
        $msg = isset($options['msg']) ? $options['msg'] : '';
        $url = isset($options['url']) ? $options['url'] : '';
        if (isset($options['type'])) {
            $type = $options['type'];
        } else {
            if (Request::isAjax()) {
                $type = 'json';
            } else {
                $type = 'html';
            }
        }
        if ($type == 'json') {
            $this->json([
                'results' => 'success',
                'msg' => $msg,
                'url' => $url
            ]);
        } else {
            $results = 'success';
            include '../library/PurePHP/Mvc/location.php';
        }
        die();
    }
    protected function failure($options = [])
    {
        $msg = isset($options['msg']) ? $options['msg'] : '';
        $url = isset($options['url']) ? $options['url'] : '';
        if (isset($options['type'])) {
            $type = $options['type'];
        } else {
            if (Request::isAjax()) {
                $type = 'json';
            } else {
                $type = 'html';
            }
        }
        if ($type == 'json') {
            $this->json([
                'results' => 'failure',
                'msg' => $msg,
                'url' => $url
            ]);
        } else {
            $results = 'failure';
            include '../library/PurePHP/Mvc/location.php';
        }
        die();
    }
    protected function json($data)
    {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    protected function rdirect($url)
    {
        header('location:' . $url);
    }
    
    public function __call($fun, $arg)
    {
        header("HTTP/1.1 404 Not Found");  
        header("Status: 404 Not Found");
        die();
    }
}
