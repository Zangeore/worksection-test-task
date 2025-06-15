<?php

namespace App\Models;

use Core\Data\Model;

class Task extends Model
{
    /**
     * @var int|null
     */
    public $id;
    /**
     * @var string
     */
    public $task;
    /**
     * @var string
     */
    public $status;
    /**
     * @var string
     */
    public $created_at;
    /**
     * @var string
     */
    public $updated_at;
}
