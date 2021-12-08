<?php


if(!function_exists('timely'))
{
    function timely($timely, $num)
    {
        switch($timely)
        {
            case 'day':
                if(1 == $num || 0 == $num)
                    return __('daily', 'leo_product');

                if(30 == $num)
                    return __('monthly', 'leo_product');

                if(60 == $num)
                    return __('bimonthly');

                return sprintf(__('every %d '.$timely.'s', 'leo_product'), $num);
                break;

            case 'month':
                if(1 == $num || 0 == $num)
                    return __('monthly', 'leo_product');

                if(12 == $num)
                    return __('annually', 'leo_product');

                return sprintf(__('every %d '.$timely.'s', 'leo_product'), $num);
                break;

            case 'year':
                if(1 == $num || 0 == $num)
                    return __('annually', 'leo_product');

                return sprintf(__('every %d '.$timely.'s', 'leo_product'), $num);
                break;
        } // switch
    }
}
if(!function_exists('time_during'))
{
    function time_during($timely, $num)
    {
        $num = intval($num);
        switch($timely)
        {
            case 'day':
                if(1 == $num || 0 == $num)
                    return __('1 day', 'leo_product');

                if(30 == $num)
                    return __('1 month', 'leo_product');

                if($num > 30 && ($num%30 == 0 || $num%30 == 15))
                    return sprintf(__('%s months', 'leo_product'), $num/30);

                return $num.' '.__($timely.'s', 'leo_product');
                break;

            case 'month':
                if(1 == $num || 0 == $num)
                    return __('1 month', 'leo_product');

                if(12 == $num)
                    return __('1 year', 'leo_product');

                if($num > 12 && ($num%12 == 0 || $num%12 == 6))
                {
                    return sprintf(__('%s years', 'leo_product'), $num/12);
                }

                return $num.' '.__($timely.'s', 'leo_product');
                break;

            case 'year':
                if(1 == $num || 0 == $num)
                    return __('1 year', 'leo_product');

                return $num.' '.__($timely.'s', 'leo_product');
                break;
        } // switch
    }
}
if(!function_exists('_postdata'))
{
    function _postdata($key, $val='')
    {
        return(isset($_POST[$key])? $_POST[$key] : $val);
    }
}

if(!function_exists('_getdata'))
{
    function _getdata($key, $val='')
    {
        return(isset($_GET[$key])? $_GET[$key] : $val);
    }
}

if(!function_exists('_replace'))
{
    function _replace($txt, $search=array())
    {
        foreach($search as $k=>$v)
        {
            $txt = str_replace($k,$v,$txt);
        }
        return $txt;
    }
}

if(!function_exists('_trim_chars'))
{
    function _trim_chars($str, $n=20, $sep='...')
    {
        if(strlen($str)<$n) return $str;
        $html = substr($str,0,$n);
        $html = substr($html,0,strrpos($html,' '));
        return $html.$sep;
    } 
}