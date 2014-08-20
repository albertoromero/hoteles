<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Debuga um valor
 *
 * @param mixed $var valor a ser debugado
 * @param boolean $showHtml exibe o código html
 * @param boolean $showFrom exibe o local do debug
 * @param string $layout layout que sera exibido o debug html|text
 */
if (!function_exists('debug')) {
    function debug($var = false, $showHtml = false, $showFrom = true, $layout = 'html')
    {
        if (defined('ENVIRONMENT') && ENVIRONMENT == 'development') {
            $file = '';
            $line = '';
            $lineInfo = '';
            $layout = $layout == 'html' || $layout == 'text' ? $layout : 'text';

            $templates['html']  = '<div class="debug-output">'."\n";
            $templates['html'] .= '%s'."\n";
            $templates['html'] .= '<pre class="cake-debug">'."\n";
            $templates['html'] .= '%s'."\n";
            $templates['html'] .= '</pre>'."\n";
            $templates['html'] .= '</div>'."\n";

            $templates['text']  = '%s'."\n";
            $templates['text'] .= '########## DEBUG ##########'."\n";
            $templates['text'] .= '%s'."\n";
            $templates['text'] .= '###########################'."\n";

            if ($showFrom) {
                $trace = debug_backtrace();
                $file = substr(str_replace(BASEPATH, '', $trace[0]['file']), 1);
                $line = $trace[0]['line'];

                if ($layout == 'html') {
                    $lineInfo = sprintf('<span><strong>%s</strong> (line <strong>%s</strong>)</span>', $file, $line);
                } else {
                    $lineInfo = sprintf('%s (line %s)', $file, $line);
                }
            }
            if ($var === true || $var === false) {
                $var = $var === true ? 'true' : 'false';
            } else {
                $var = print_r($var, true);
            }
            if ($showHtml) {
                $var = str_replace('<', '&lt;', str_replace('>', '&gt;', $var));
            }

            printf($templates[$layout], $lineInfo, $var);
        }
    }
}
