<?php
    /** @var $game ?\App\Model\Games */
?>

<div class="form-group">
    <label for="name">Name</label>
    <input type="text" id="name" name="games[name]" value="<?= $game ? $game->getName() : '' ?>">
</div>

<div class="form-group">
    <label for="rating">Rating</label>
    <input type="text" id="rating" name="games[rating]" value="<?=$game ? $game->getRating() : '' ?>">
</div>

<div class="form-group">
    <label for="info">Info</label>
    <textarea id="info" name="games[info]"><?= $game? $game->getInfo() : '' ?></textarea>
</div>

<div class="form-group">
    <label></label>
    <input type="submit" value="Submit">
</div>
