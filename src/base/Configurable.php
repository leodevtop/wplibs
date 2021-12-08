<?php

/**
 * @link https://github.com/yiisoft/yii2/blob/master/framework/base/Configurable.php
 */
namespace vustech\wp\base;

/**
 * Configurable is the interface that should be implemented by classes who support configuring
 * its properties through the last parameter to its constructor.
 *
 * The interface does not declare any method. Classes implementing this interface must declare their constructors
 * like the following:
 *
 * ```php
 * public function __constructor($param1, $param2, ..., $config = [])
 * ```
 * @author Yii Framework
 * @since 2.0.13
 */
interface Configurable
{
}