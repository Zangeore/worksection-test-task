<?php
$this->assetManager->addScript('/js/main.js', true);
?>
<c-task-group>
    <?php
    foreach ($tasks as $task) {
        ?>
        <c-task id="<?= $task->id ?>"
                value="<?= htmlspecialchars($task->task, ENT_QUOTES) ?>" status="<?= $task->status ?>"></c-task>
        <?php
    }
    ?>
    <c-task placeholder="Enter task name ..."></c-task>
</c-task-group>
