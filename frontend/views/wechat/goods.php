<table border="1" cellpadding="0" cellspacing="0">
    <tr>
        <th>商品图片</th>
        <th>商品名称</th>
        <th>商品介绍</th>
        <th>商品价格</th>
    </tr>
    <?php

        echo '<tr>';
        echo '<td>'.'<img width="100" src="http://home.zhjun520.top'.$goods->logo.'"/>'.'</td>';
        echo '<td>'.$goods->name.'</td>';
        echo '<td>'.$goods->intro->content.'</td>';
        echo '<td>'.$goods->shop_price.'</td>';
        echo '</tr>';

    ?>

</table>