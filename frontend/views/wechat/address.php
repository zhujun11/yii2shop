<table border="1" cellpadding="0" cellspacing="0">
    <tr>
        <th>收货人</th>
        <th>详细地址</th>
        <th>手机号</th>
    </tr>
    <?php foreach ($allAddress as $address):?>
    <tr>
        <td><?=$address->name?></td>
        <td><?=$address->province->name.$address->city->name.$address->area->name.$address->address?></td>
        <td><?=$address->tel?></td>
    </tr>
    <?php endforeach; ?>
</table>