<?php

use App\Models\CrowdFunding;
return [
    'funding' => [
        'prompt' => [
                CrowdFunding::APPLYING  => "您的互助已提交，请您耐心等待审核通过。<br/>一般情况下，审核将在一个工作日内完成。",
                CrowdFunding::WAITING   => "您的互助申请已经审批通过，已进入排队序列轮后，请及时关注互助进展。",
                CrowdFunding::USING     => "您正在使用互助金，请您在到期日前归还。",
                CrowdFunding::COMPLETED => "您的互助已完成，已归还互助金。<br/>感谢您的参与！",
                CrowdFunding::CANCELED  => "您的互助已取消，详情请资讯客服。<br/>感谢您的参与！"
        ],
        'icon' => [
                CrowdFunding::APPLYING  => "time",
                CrowdFunding::WAITING   => "pending",
                CrowdFunding::USING     => "vehicle",
                CrowdFunding::COMPLETED => "fact-check",
                CrowdFunding::CANCELED  => "close-rectangle"
        ],
        // 'rules' => "
        // <ol>
        //     <li>公享汽车互助社是由东家公享汽车车管家合伙人共同约定设立的，并为车管家众筹汽车使用费提供互助服务。</li>
        //     <li>参加公享汽车使用费互助社的人员应当是《东通公享》平台车管家企业的普通合伙人。</li>
        //     <li>参加公享汽车使用费互助金众筹的每位车管家应当提供5万元无息众筹风险保证金。</li>
        //     <li>公享汽车使用费互助金均为无息，互助金使用期为3个月内，逾期者，《东通公享》车辆提供方有权收回车辆，并扣除其使用期间内的使用费后退还余款。</li>
        //     <li>车管家使用互助金归还后，本互助金合同自动终止。同时，众筹风险保证金即日自动转为资产配置款项，按资产配置的相关合同约定条款执行《资产配置》合同。</li>
        // </ol>
        // "
    ]
];
