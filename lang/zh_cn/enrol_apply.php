<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    enrol_apply
 * @copyright  emeneo.com (http://emeneo.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// The name of your plug-in. Displayed on admin menus.
$string['enrolname'] = '选课确认';
$string['pluginname'] = '选课确认';
$string['pluginname_desc'] = '用户可以通过这个插件申请选课。然后教师或者站点管理员需要批准选课请求以使选课成功。';

$string['confirmmailsubject'] = '确认邮件的标题';
$string['confirmmailcontent'] = '确认邮件的内容';
$string['cancelmailsubject'] = '取消邮件的标题';
$string['cancelmailcontent'] = '取消邮件的内容';
$string['confirmmailcontent_desc'] = '请通过下列特殊标记来用Moodle中的数据取代邮件正文中内容。<br/>{firstname}: 用户名字; {content}: 课程名字; {lastname}: 用户姓氏; {username}: 用户的注册名';
$string['cancelmailcontent_desc'] = '请通过下列特殊标记来用Moodle中的数据取代邮件正文中内容。<br/>{firstname}: 用户名字; {content}: 课程名字; {lastname}: 用户姓氏; {username}: 用户的注册名';

$string['confirmusers'] = '确认选课';

$string['coursename'] = '课程';
$string['applyuser'] = '名/姓';
$string['applyusermail'] = '电子邮件';
$string['applydate'] = '选课日期';
$string['btnconfirm'] = '确认';
$string['btncancel'] = '取消';
$string['enrolusers'] = '选课的用户';

$string['status'] = '允许选课确认';
$string['confirmenrol'] = '管理申请';

$string['apply:config'] = '配置选课确认的实例';
$string['apply:manageapplications'] = '管理选课申请';
$string['apply:unenrol'] = '从课程中取消用户的选课';
$string['apply:unenrolself'] = '从课程中取消自身的选课';

$string['notification'] = '<b>选课申请已发送成功</b>。<br/><br/>申请通过后您会收到邮件通知。';

$string['sendmailtoteacher'] = '向教师发送邮件通知';
$string['sendmailtomanager'] = '向管理员发送邮件通知';
$string['mailtoteacher_suject'] = '新的选课申请！';
$string['editdescription'] = '文本区描述';
$string['comment'] = '评论';
$string['applymanage'] = '管理选课申请';

$string['status_desc'] = '允许内部选课的用户进入课程';
