<?php


$callback = $_GET['CKEditorFuncNum'];
$file_name = $_FILES['upload']['name'];
$file_name_tmp = $_FILES['upload']['tmp_name'];
$file_new_name = my3::basePath().'images/upload/'; // ��������� ����� - ����� ��� ���������� ������. (����� ����� �� ������)
$full_path = $file_new_name.$file_name;
$http_path = my3::baseUrl().'images/upload/'.$file_name; // ����� ����������� ��� ��������� ����� http
$error = '';
if( move_uploaded_file($file_name_tmp, $full_path) )
{
// ����� �������� ��� ��� �������� ���������� �������� �����
} else
{
$error = '������, ��������� ������� �����'; // ��� ������ �������� � �������� ���� ������ �� ���� ��������� ����
$http_path = '';
}
echo "<script type='text/javascript'>// <![CDATA[
window.parent.CKEDITOR.tools.callFunction(".$callback.",  \"".$http_path."\", \"".$error."\" );
// ]]></script>";
