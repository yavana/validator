<?php

/**
 * 测试用例
 * 这是一个很不规范的测试用例，请不要在意这些细节~~
 */

include_once(dirname(__DIR__) . '/Src/Code/ConstService.php');
include_once(dirname(__DIR__) . '/Src/Rules/NormalRules.php');
include_once(dirname(__DIR__) . '/Src/Validator.php');
use FurthestWorld\Validator\Src\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase {

    public function testValidateParams() {
        $params = [
            'domain'    => 'furthestworld.com',
            'member_id' => 10,
        ];
        Validator::extend('extend_test', new TestExtendRules());
        Validator::formatParams(
            $params,
            [
                'domain'    => ['format_rule' => 'strtoupper', 'default_value' => ''],
                'member_id' => ['format_rule' => 'formatExtendMemberId:domain']
            ]
        );
        Validator::validateParams(
            $params,
            [
                'domain'    => ['check_rule' => 'number|string#string:10,500'],
                'member_id' => ['check_rule' => 'extendEq:20#number'],
            ]
        );

        if (!Validator::pass()) {
            var_dump(Validator::getErrors());
        } else {
            var_dump("\r\n验证通过！");
        }
    }


}

/**
 * @node_name 测试扩展验证规则实例
 * Class TestExtendRules
 */
class TestExtendRules {

    /**
     * @node_name
     * @param     $param_value
     * @param int $eq
     * @return array
     */
    public static function checkExtendEq($param_value, $eq = 0) {
        if ($eq == $param_value) {
            return [1, '验证成功'];
        }
        return [999, '呵呵:不相等~'];
    }

    public static function formatExtendMemberId($param_value) {
        return intval($param_value) + 20;
    }

}
