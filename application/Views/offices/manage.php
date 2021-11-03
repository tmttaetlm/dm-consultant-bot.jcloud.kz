<div class="rightSide">
    <label for="selectRegion">Выберите область, город: </label>
    <?php echo $data['select'] ?>
    <select id="selectCity" disabled></select>
    <br>
    <label for="officePhone">Введите номер телефона: </label>
    <input type="tel" id="officePhone" placeholder="+7 ___ ___ ____" maxlength="17" autocomplete="off" />
    <br>
    <label for="officeAdres">Введите адрес: </label><br>
    <textarea id="officeAdres" rows="3" cols="30"></textarea>
    <br>
    <label for="officeMediaUrl">Вставьте ссылку на фото отделения/схему проезда: </label><br>
    <input id="officeMediaUrl" style="width: 500px" />
    <br>
    <button id="addOffice">Добавить</button>
</div>