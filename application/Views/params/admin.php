<div>
    <h2 style="margin-bottom: 10px">Настройка администратора</h2>
    <label for="currentAdmin">Текущий администратор:</label>
    <input id="currentAdmin" type="text" readonly value="<?php echo $data['params']['admin_name'] ?>"/>
    <label for="adminId">ID пользователя:</label>
    <input id="adminId" type="text" readonly value="<?php echo $data['params']['admin_id'] ?>"/>
    <br>
    <label for="selectAdmin">Выбрать администратора:</label>
    <select name="selectAdmin" id="selectAdmin">
        <?php foreach ($data['items'] as $item=>$value):?>	
        <option value="<?php if (is_array($value)) { array_key_exists('value', $value) ? print($value['value']) : print(''); } else { print($value); } ?>"
                data-oid="<?php if (is_array($value)) { array_key_exists('oid', $value) ? print($value['oid']) : print(''); } else { print(''); } ?>"
            <?php if (is_array($value)) {
                    if (array_key_exists('selected', $data)) {
                        if ($value['value']==$data['selected']) { echo 'selected'; }
                    }
                } else {
                        if ($value==$data['selected']) { echo 'selected'; }
                };
                array_key_exists('data-oid', $data['items']) ? print('data-oid="'.$data['data-oid'].'"') : print(""); ?>>
                <?php if (is_array($value)) { array_key_exists('item', $value) ? print($value['item']) : print(''); } else { print($value); } ?>
        </option>
        <?php endforeach;?>
    </select>
    <label for="setName">Назначить имя:</label>
    <input id="setName" type="text" />
    <button id="setAdmin">Назначить</button>
</div>
<hr size="1" style="margin-top: 15px; margin-bottm: 15px;">
<div>
    <h2 style="margin-bottom: 10px">Настройка сообщений</h2>
    <label for="welcomeMsg">Приветственное сообщение:</label><br>
    <textarea id="welcomeMsg" rows="5" class="msgSettings"><?php echo $data['params']['welcome_text'] ?></textarea>
</div>