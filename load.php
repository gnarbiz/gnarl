<?php
/* gnar.biz URL gnarlification class
 * Examples: 
 *   create a new gnarURL: $gnar = new gnarURL('http://foo.bar/lkjlkahdlkahslkjashdlkh');
 *                         echo $gnar->gnarurl; 
 *   lookup gnarled hash:  $gnar = new gnarURL(null,'X7ad1');
 *                         echo $gnar->redir;i
 */
require_once 'config.php';
function __autoload($class_name) {
    include $class_name . '.php';
}
?>
