<table class="<?php echo $data['class'];?>">
    <caption><?php echo $data['caption']; ?></caption>
    <tr>
    <?php foreach ($data['columns'] as $column=>$header):
        if ($header != '') { echo '<th>'.$header.'</th>'; };
    endforeach;?>
    </tr>
    <?php foreach ($data['tableData'] as $row):?>
        <tr>
            <?php foreach ($data['columns'] as $column=>$header):?>
                <td><?php echo $row[$column]; ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach;?>
</table>