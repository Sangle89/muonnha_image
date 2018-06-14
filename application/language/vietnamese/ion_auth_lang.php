<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Lang - English
*
* Author: Ben Edmunds
*         ben.edmunds@gmail.com
*         @benedmunds
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.14.2010
*
* Description:  English language file for Ion Auth messages and errors
*
*/
// Account Creation
$lang['account_creation_successful']            = 'Tạo tài khoản thành công!';
$lang['account_creation_unsuccessful']          = 'Không thể tạo tài khoản';
$lang['account_creation_duplicate_email']       = 'Email đã tồn tại hoặc không đúng';
$lang['account_creation_duplicate_identity']    = 'Identity Already Used or Invalid';
$lang['account_creation_missing_default_group'] = 'Nhóm mặc định chưa được cài đặt';
$lang['account_creation_invalid_default_group'] = 'Tên nhóm mặc định không đúng';
// Password
$lang['password_change_successful']          = 'Mật khẩu cập nhật thành công';
$lang['password_change_unsuccessful']        = 'Không thể đổi mật khẩu';
$lang['forgot_password_successful']          = 'Mật khẩu phục hồi đã được gửi qua mail';
$lang['forgot_password_unsuccessful']        = 'Không thể khôi phục mật khẩu';
// Activation
$lang['activate_successful']                 = 'Tài khoản đã được kích hoạt thành công, bạn hãy <a href="/dang-nhap">đăng nhập</a> và bắt đầu đăng tin.';
$lang['activate_unsuccessful']               = 'Không thể kích hoạt tài khoản';
$lang['deactivate_successful']               = 'Account De-Activated';
$lang['deactivate_unsuccessful']             = 'Unable to De-Activate Account';
$lang['activation_email_successful']         = 'Activation Email Sent';
$lang['activation_email_unsuccessful']       = 'Unable to Send Activation Email';
// Login / Logout
$lang['login_successful']                    = 'Đăng nhập thành công';
$lang['login_unsuccessful']                  = 'Thông tin đăng nhập không đúng';
$lang['login_unsuccessful_not_active']       = 'Tài khoản đã kích hoạt';
$lang['login_timeout']                       = 'Temporarily Locked Out.  Try again later.';
$lang['logout_successful']                   = 'Đăng xuất thành công';
// Account Changes
$lang['update_successful']                   = 'Cập nhật thông tin tài khoản thành công';
$lang['update_unsuccessful']                 = 'Không thể cập nhật thông tin tài khoản';
$lang['delete_successful']                   = 'Người dùng đã bị xóa';
$lang['delete_unsuccessful']                 = 'Không thể xóa người dùng';
// Groups
$lang['group_creation_successful']           = 'Group created Successfully';
$lang['group_already_exists']                = 'Group name already taken';
$lang['group_update_successful']             = 'Group details updated';
$lang['group_delete_successful']             = 'Group deleted';
$lang['group_delete_unsuccessful']           = 'Unable to delete group';
$lang['group_delete_notallowed']             = 'Can\'t delete the administrators\' group';
$lang['group_name_required']                 = 'Group name is a required field';
$lang['group_name_admin_not_alter']          = 'Admin group name can not be changed';
// Activation Email
$lang['email_activation_subject']            = 'Email Kích hoạt tài khoản';
$lang['email_activate_heading']              = 'Kích hoạt tài khoản cho %s';
$lang['email_activate_subheading']           = 'Vui lòng nhấp vào liên kết %s.';
$lang['email_activate_link']                 = 'Kích hoạt tài khoản của bạn';
// Forgot Password Email
$lang['email_forgotten_password_subject']    = 'Forgotten Password Verification';
$lang['email_forgot_password_heading']       = 'Reset Password for %s';
$lang['email_forgot_password_subheading']    = 'Please click this link to %s.';
$lang['email_forgot_password_link']          = 'Reset Your Password';
// New Password Email
$lang['email_new_password_subject']          = 'Mật khẩu mới';
$lang['email_new_password_heading']          = 'Mật khẩu mới cho %s';
$lang['email_new_password_subheading']       = 'Mật khẩu của bạn đã được gửi đến: %s';