<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Auth Lang - English
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Author: Daniel Davis
*         @ourmaninjapan
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.09.2013
*
* Description:  English language file for Ion Auth example views
*
*/
// Errors
$lang['error_csrf'] = 'This form post did not pass our security checks.';
// Login
$lang['login_heading']         = 'Đăng nhập';
$lang['login_subheading']      = 'Vui lòng đăng nhập bằng email/tên đăng nhập và mật khẩu.';
$lang['login_identity_label']  = 'Email/Tên đăng nhập:';
$lang['login_password_label']  = 'Mật khẩu:';
$lang['login_remember_label']  = 'Ghi nhớ:';
$lang['login_submit_btn']      = 'Đăng nhập';
$lang['login_forgot_password'] = 'Quên mật khẩu?';
// Index
$lang['index_heading']           = 'Users';
$lang['index_subheading']        = 'Below is a list of the users.';
$lang['index_fname_th']          = 'Họ';
$lang['index_lname_th']          = 'Tên';
$lang['index_email_th']          = 'Email';
$lang['index_groups_th']         = 'Nhóm';
$lang['index_status_th']         = 'Trạng thái';
$lang['index_action_th']         = 'Hành động';
$lang['index_active_link']       = 'Kích hoạt';
$lang['index_inactive_link']     = 'Hủy kích hoạt';
$lang['index_create_user_link']  = 'Tạo tài khoản mới';
$lang['index_create_group_link'] = 'Tạo nhóm mới';
// Deactivate User
$lang['deactivate_heading']                  = 'Deactivate User';
$lang['deactivate_subheading']               = 'Are you sure you want to deactivate the user \'%s\'';
$lang['deactivate_confirm_y_label']          = 'Yes:';
$lang['deactivate_confirm_n_label']          = 'No:';
$lang['deactivate_submit_btn']               = 'Submit';
$lang['deactivate_validation_confirm_label'] = 'confirmation';
$lang['deactivate_validation_user_id_label'] = 'user ID';
// Create User
$lang['create_user_heading']                           = 'Tạo tài khoản';
$lang['create_user_subheading']                        = 'Vui lòng nhập thông tin vào ô bên dưới.';
$lang['create_user_fname_label']                       = 'Họ:';
$lang['create_user_lname_label']                       = 'Tên:';
$lang['create_user_company_label']                     = 'Công ty:';
$lang['create_user_identity_label']                    = 'Tên đăng nhập:';
$lang['create_user_email_label']                       = 'Email:';
$lang['create_user_phone_label']                       = 'Số điện thoại:';
$lang['create_user_address_label']                     = 'Địa chỉ:';
$lang['create_user_password_label']                    = 'Mật khẩu:';
$lang['create_user_password_confirm_label']            = 'Nhập lại mật khẩu:';
$lang['create_user_submit_btn']                        = 'Tạo tài khoản';
$lang['create_user_validation_fname_label']            = 'Họ';
$lang['create_user_validation_lname_label']            = 'Tên';
$lang['create_user_validation_identity_label']         = 'Tên đăng nhập';
$lang['create_user_validation_email_label']            = 'Đại chỉ Email';
$lang['create_user_validation_phone_label']            = 'Số điện thoại';
$lang['create_user_validation_address_label']            = 'Đại chỉ';
$lang['create_user_validation_company_label']          = 'Công ty';
$lang['create_user_validation_password_label']         = 'Mật khẩu';
$lang['create_user_validation_password_confirm_label'] = 'Nhập lại mật khẩu';
// Edit User
$lang['edit_user_heading']                           = 'Sửa tài khoản';
$lang['edit_user_subheading']                        = 'Please enter the user\'s information below.';
$lang['edit_user_fname_label']                       = 'Họ:';
$lang['edit_user_lname_label']                       = 'Tên:';
$lang['edit_user_company_label']                     = 'Công ty:';
$lang['edit_user_email_label']                       = 'Email:';
$lang['edit_user_address_label']                     = 'Địa chỉ:';
$lang['edit_user_phone_label']                       = 'Số điện thoại:';
$lang['edit_user_password_label']                    = 'Mật khẩu: (nếu muốn thay đổi mật khẩu)';
$lang['edit_user_password_confirm_label']            = 'Nhập lại mật khẩu: (nếu muốn thay đổi mật khẩu)';
$lang['edit_user_groups_heading']                    = 'Thành viên của nhóm';
$lang['edit_user_submit_btn']                        = 'Lưu tài khoản';
$lang['edit_user_validation_fname_label']            = 'Họ';
$lang['edit_user_validation_lname_label']            = 'Tên';
$lang['edit_user_validation_email_label']            = 'Địa chỉ Email';
$lang['edit_user_validation_phone_label']            = 'Số điện thoại';
$lang['edit_user_validation_company_label']          = 'Công ty';
$lang['edit_user_validation_address_label']          = 'Địa chỉ';
$lang['edit_user_validation_groups_label']           = 'Nhóm';
$lang['edit_user_validation_password_label']         = 'Mật khẩu';
$lang['edit_user_validation_password_confirm_label'] = 'Nhập lại mật khẩu';
// Create Group
$lang['create_group_title']                  = 'Create Group';
$lang['create_group_heading']                = 'Create Group';
$lang['create_group_subheading']             = 'Please enter the group information below.';
$lang['create_group_name_label']             = 'Group Name:';
$lang['create_group_desc_label']             = 'Description:';
$lang['create_group_submit_btn']             = 'Create Group';
$lang['create_group_validation_name_label']  = 'Group Name';
$lang['create_group_validation_desc_label']  = 'Description';
// Edit Group
$lang['edit_group_title']                  = 'Edit Group';
$lang['edit_group_saved']                  = 'Group Saved';
$lang['edit_group_heading']                = 'Edit Group';
$lang['edit_group_subheading']             = 'Please enter the group information below.';
$lang['edit_group_name_label']             = 'Group Name:';
$lang['edit_group_desc_label']             = 'Description:';
$lang['edit_group_submit_btn']             = 'Save Group';
$lang['edit_group_validation_name_label']  = 'Group Name';
$lang['edit_group_validation_desc_label']  = 'Description';
// Change Password
$lang['change_password_heading']                               = 'Đổi mật khẩu';
$lang['change_password_old_password_label']                    = 'Mật khẩu cũ:';
$lang['change_password_new_password_label']                    = 'Mật khẩu mới (ít nhất %s ký tự):';
$lang['change_password_new_password_confirm_label']            = 'Nhập lại mật khẩu mới:';
$lang['change_password_submit_btn']                            = 'Lưu lại';
$lang['change_password_validation_old_password_label']         = 'Mật khẩu cũ';
$lang['change_password_validation_new_password_label']         = 'Mật khẩu mới';
$lang['change_password_validation_new_password_confirm_label'] = 'Nhập lại mật khẩu mới';
// Forgot Password
$lang['forgot_password_heading']                 = 'Quên mật khẩu';
$lang['forgot_password_subheading']              = 'Please enter your %s so we can send you an email to reset your password.';
$lang['forgot_password_email_label']             = '%s:';
$lang['forgot_password_submit_btn']              = 'Gửi';
$lang['forgot_password_validation_email_label']  = 'Địa chỉ Email';
$lang['forgot_password_identity_label'] = 'Identity';
$lang['forgot_password_email_identity_label']    = 'Email';
$lang['forgot_password_email_not_found']         = 'No record of that email address.';
// Reset Password
$lang['reset_password_heading']                               = 'Thay đổi mật khẩu';
$lang['reset_password_new_password_label']                    = 'Mật khẩu mới (ít nhất %s ký tự):';
$lang['reset_password_new_password_confirm_label']            = 'Nhập lại mật khẩu mới:';
$lang['reset_password_submit_btn']                            = 'Thay đổi';
$lang['reset_password_validation_new_password_label']         = 'Mật khẩu mới';
$lang['reset_password_validation_new_password_confirm_label'] = 'Nhập lại mật khẩu mới';