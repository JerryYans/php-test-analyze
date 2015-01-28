<?php

//php的引用导致的问题
//组合数据
//***********//
foreach ($sub_user_list as $sub_user_id_key => &$sub_user_info){
    $loupan_id = $sub_user_info['loupan_id'];
    //通过楼盘id获得父帐号id
    $main_user_id = $main_user_list[$loupan_id]['user_id'];
    //通过父帐号id获得父帐号电话
    $main_user_phone = $user_phone_list[$main_user_id];
    $sub_user_info['main_user_id'] = empty($main_user_id) ? 0 : $main_user_id;
    $sub_user_info['main_user_phone'] = empty($main_user_phone) ? "" : $main_user_phone;;
    $sub_user_info['sub_user_phone'] = empty($user_phone_list[$sub_user_id_key]) ? "" : $user_phone_list[$sub_user_id_key];
}

//add_sql
$add_sql = "insert into table (city_id,loupan_id,loupan_name,sub_user_id,sub_user_name,sub_user_phone,main_user_phone,goods_prop_num) values ";
foreach ($user_prop_list as $user_prop){
    $user_id = $user_prop['user_id'];
    $sub_user_info = $sub_user_list[$user_id];//***********//
    $loupan_ids = $user_prop['loupan_id'];
    $add_sql .= "(".$user_prop['city_id'].",";//city_id
    $add_sql .= $user_prop['loupan_id'].",";//loupan_id
    $add_sql .= "'{$sub_user_info['loupan_name']}'".",";//loupan_name
    $add_sql .= $user_id.",";//sub_user_id
    $add_sql .= "'{$sub_user_info['user_name']}'".",";//sub_user_name
    $add_sql .= "'{$sub_user_info['sub_user_phone']}'".",";//sub_user_phone
    $add_sql .= "'{$sub_user_info['main_user_phone']}'".",";//main_user_phone
    $add_sql .= $user_prop['count']."),";//goods_prop_num
}

//上面代码有问题！&$sub_user_info  
//注意 //***********//部分
//$sub_user_info = $sub_user_list[$user_id];
//此时 会改变  $sub_user_list里面的最后一个值！！！！ 因为$sub_user_info此时为指向$sub_user_list最后一个元素的指针